<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\LegislativeProposal;
use App\Models\Deputy;

class SyncProposalAuthorsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $proposalId;

    public function __construct($proposalId)
    {
        $this->proposalId = $proposalId;
        $this->onQueue('authors');
    }

    public function handle()
    {
        $proposal = LegislativeProposal::find($this->proposalId);
        if (!$proposal) {
            Log::warning("⚠️ Proposição não encontrada: ID {$this->proposalId}");
            return;
        }

        Log::info("🔍 Buscando autores para proposição {$proposal->external_id}");

        $response = Http::get("https://dadosabertos.camara.leg.br/api/v2/proposicoes/{$proposal->external_id}/autores");
        if ($response->failed()) {
            Log::error("❌ Falha ao buscar autores: {$proposal->external_id}");
            return;
        }

        $authorsData = $response->json('dados');
        
        // Limpa autores existentes
        $proposal->authors()->delete();

        $authorsCount = 0;
        $linkedAuthors = 0;

        foreach ($authorsData as $author) {
            try {
                $authorCreated = $this->processAuthor($proposal, $author);
                if ($authorCreated) {
                    $authorsCount++;
                    if ($authorCreated->deputy_id) {
                        $linkedAuthors++;
                    }
                }
            } catch (\Exception $e) {
                Log::error("❌ Erro ao processar autor", [
                    'proposal' => $proposal->id,
                    'author' => $author['nome'] ?? 'Desconhecido',
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info("👥 {$proposal->external_id}: {$authorsCount} autores sincronizados ({$linkedAuthors} vinculados)");
    }

    protected function processAuthor($proposal, $authorData)
    {
        $deputyId = null;
        $uri = $authorData['uri'] ?? '';

        // Método confiável para extrair ID do deputado
        if (strpos($uri, '/deputados/') !== false) {
            $parts = explode('/', rtrim($uri, '/'));
            $externalDeputyId = end($parts);
            
            if (is_numeric($externalDeputyId)) {
                $deputy = Deputy::where('external_id', $externalDeputyId)->first();
                $deputyId = $deputy ? $deputy->id : null;
            }
        }

        return $proposal->authors()->create([
            'name' => $authorData['nome'] ?? 'Nome não disponível',
            'type' => $authorData['tipo'] ?? 'Tipo não disponível',
            'uri' => $uri,
            'deputy_id' => $deputyId,
            'is_proponent' => ($authorData['proponente'] ?? 0) == 1,
        ]);
    }
}