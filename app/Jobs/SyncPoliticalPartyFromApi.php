<?php

namespace App\Jobs;

use App\Models\PoliticalParty;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncPoliticalPartyFromApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $externalId
    ) {}

    public function handle(): void
    {
        $response = Http::get("https://dadosabertos.camara.leg.br/api/v2/partidos/{$this->externalId}");

        if ($response->failed()) {
            Log::error("Erro ao sincronizar partido ID {$this->externalId}", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return;
        }

        $partyData = $response->json('dados');

        PoliticalParty::updateOrCreate(
            ['external_id' => $this->externalId],
            [
                'acronym' => $partyData['sigla'],
                'name' => $partyData['nome'],
            ]
        );
    }
}
