<?php

namespace App\Jobs;

use App\Models\Deputy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DispatchDeputyExpensesSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        Deputy::chunkById(100, function ($deputies) {
            foreach ($deputies as $deputy) {
                if ($deputy?->id && $deputy->external_id) {
                    SyncDeputyExpensesFromApi::dispatch($deputy->id)
                        ->onQueue('expenses');
                }
            }
        });
    }
}
