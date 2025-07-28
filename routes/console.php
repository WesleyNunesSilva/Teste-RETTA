<?php

use Illuminate\Support\Facades\Schedule;
use App\Jobs\SyncDeputiesFromApi;
use App\Jobs\DispatchDeputyExpensesSync;
use App\Jobs\ImportLegislativeProposalsJob;

// Sincronização diária completa
Schedule::job(new SyncDeputiesFromApi(1, 100))
    ->everyMinute()
    ->then(function () {
        // Disparar despesas após deputados
        DispatchDeputyExpensesSync::dispatch()->onQueue('default');
    })
    ->then(function () {
        // Disparar proposições após despesas
        ImportLegislativeProposalsJob::dispatch()->onQueue('proposals');
    });

// Sincronização incremental rápida
Schedule::job(new SyncDeputiesFromApi(1, 20, true))
    ->everyMinute()
    ->withoutOverlapping();