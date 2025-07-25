<?php
// tests/Feature/Deputy/SyncDeputiesTest.php

use App\Jobs\SyncDeputiesJob;
use App\Models\Deputy;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use function Pest\Laravel\artisan;

it('dispatches the sync deputies job', function () {
    Queue::fake();

    artisan('sync:deputies')->assertExitCode(0);

    Queue::assertPushed(SyncDeputiesJob::class);
});

it('syncs deputies from API inside the job', function () {
    Http::fake([
        'https://dadosabertos.camara.leg.br/api/v2/deputados*' => Http::response([
            'dados' => [
                [
                    'id' => 100,
                    'nome' => 'João da Silva',
                    'siglaUf' => 'SP',
                    'siglaPartido' => 'PT',
                    'email' => 'joao@camara.leg.br',
                    'uriPartido' => 'https://dadosabertos.camara.leg.br/api/v2/partidos/13',
                    'urlFoto' => 'https://foto.com/deputado.jpg',
                ]
            ],
            'links' => [],
        ]),
    ]);

    // Executa diretamente o job (sem passar pela fila)
    (new SyncDeputiesJob())->handle();

    expect(Deputy::where('external_id', 100)->exists())->toBeTrue();
});
