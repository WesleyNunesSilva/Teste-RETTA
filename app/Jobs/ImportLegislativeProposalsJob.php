<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LegislativeProposal;
use App\Models\Deputy;

class ImportLegislativeProposalsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $page;
    public $limit;

    public function __construct($page = 1, $limit = 100)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->onQueue('proposals');
    }

    public function handle()
    {
        Log::info("🚀 Buscando proposições - Página {$this->page}");

        // Forçar formato JSON na URL
        $response = Http::get('https://dadosabertos.camara.leg.br/api/v2/proposicoes', [
            'pagina' => $this->page,
            'itens' => $this->limit,
            'ordem' => 'DESC',
            'ordenarPor' => 'id',
        ]);

        if ($response->failed()) {
            Log::error('❌ Erro ao buscar proposições', [
                'page' => $this->page,
                'status' => $response->status(),
                'error' => $response->body()
            ]);
            return;
        }

        $data = $response->json();
        $proposals = $data['dados'] ?? [];
        $links = $data['links'] ?? [];
        
        $totalItems = $data['totalItems'] ?? 0;
        $totalPages = $data['totalPaginas'] ?? 1;

        Log::info("📦 Página {$this->page}/{$totalPages} - " . count($proposals) . " proposições");

        if (empty($proposals)) {
            Log::info("⏩ Nenhuma proposição na página {$this->page}");
            return;
        }

        foreach ($proposals as $item) {
            try {

                $proposal = LegislativeProposal::updateOrCreate(
                    ['external_id' => $item['id']],
                    [
                        'type_acronym' => $item['siglaTipo'] ?? null,
                        'number' => $item['numero'] ?? null,
                        'year' => $item['ano'] ?? null,
                        'summary' => $item['ementa'] ?? null,
                    ]
                );
                
                // Job para autores
                SyncProposalAuthorsJob::dispatch($proposal->id);
                
                Log::info("✅ Proposição {$item['id']} sincronizada");
            } catch (\Exception $e) {
                Log::error("❌ Erro na proposição {$item['id']}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        // Verificar próxima página usando os links da API
        $hasNextPage = false;
        foreach ($links as $link) {
            if ($link['rel'] === 'next') {
                $hasNextPage = true;
                break;
            }
        }

        if ($hasNextPage) {
            $nextPage = $this->page + 1;
            Log::info("➡️ Despachando próxima página: {$nextPage}");
            self::dispatch($nextPage, $this->limit)
                ->delay(now()->addSeconds(3))
                ->onQueue('proposals');
        } else {
            Log::info("🏁 Todas as páginas foram processadas! Total: {$totalItems} proposições");
        }
    }
}