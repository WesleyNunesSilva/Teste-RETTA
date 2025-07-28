<?php

namespace App\Jobs;

use App\Models\Deputy;
use App\Models\PoliticalParty;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncDeputiesFromApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $page;
    public $perPage;

    public function __construct($page = 1, $perPage = 100)
    {
        $this->page = $page;
        $this->perPage = $perPage;

        $this->onQueue('high');
        $this->timeout = 300;
        $this->tries = 3;
        $this->backoff = [60, 300];
    }

    public function handle(): void
    {
        try {
            $response = Http::timeout(30)->get('https://dadosabertos.camara.leg.br/api/v2/deputados', [
                'pagina' => $this->page,
                'itens' => $this->perPage,
                'ordem' => 'ASC',
                'ordenarPor' => 'id'
            ]);

            if ($response->failed()) {
                Log::error("Erro na sincronização dos deputados - Página {$this->page}", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return;
            }

            $deputies = $response->json('dados');
            $links = $response->json('links');

            if (empty($deputies)) {
                Log::info("Nenhum deputado encontrado na página {$this->page}");
                return;
            }

            foreach ($deputies as $data) {
                try {
                    $this->processDeputy($data);
                } catch (\Exception $e) {
                    Log::error("Erro ao processar deputado ID {$data['id']}", [
                        'error' => $e->getMessage(),
                        'deputy' => $data
                    ]);
                }
            }

            $nextPage = $this->getNextPageNumber($links);
            if ($nextPage) {
                self::dispatch($nextPage, $this->perPage)->delay(now()->addSeconds(5));
            }
        } catch (\Exception $e) {
            Log::error("Erro crítico ao sincronizar deputados - Página {$this->page}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function processDeputy(array $data): void
    {
        DB::transaction(function () use ($data) {
            $partyId = $this->extractPartyId($data);

            $party = PoliticalParty::firstOrNew(['external_id' => $partyId]);
            $party->acronym = $data['siglaPartido'];
            
            $party->save();

            if ($partyId) {
                SyncPoliticalPartyFromApi::dispatch($partyId)
                    ->onQueue('high')
                    ->delay(now()->addSeconds(2));
            }
    
            Deputy::updateOrCreate(
                ['external_id' => $data['id']],
                [
                    'political_party_id' => $party->id,
                    'name' => $data['nome'],
                    'email' => $data['email'] ?? null,
                    'state_acronym' => $data['siglaUf'],
                    'last_synced_at' => now()
                ]
            );
        });
    }

    private function extractPartyId(array $data): ?int
    {
        if (isset($data['idPartido'])) return (int) $data['idPartido'];

        if (isset($data['uriPartido']) && preg_match('/\/partidos\/(\d+)/', $data['uriPartido'], $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    private function getNextPageNumber(array $links): ?int
    {
        foreach ($links as $link) {
            if ($link['rel'] === 'next' && isset($link['href'])) {
                parse_str(parse_url($link['href'], PHP_URL_QUERY), $params);
                return $params['pagina'] ?? null;
            }
        }
        return null;
    }
}
