<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeputyController;
use App\Http\Controllers\PoliticalPartyController;
use App\Http\Controllers\LegislativeProposalController;


Route::get('/', [DeputyController::class, 'index'])->name('deputies.index');
Route::get('/deputados/{id}', [DeputyController::class, 'show'])->name('deputies.show');

Route::get('partidos', [PoliticalPartyController::class, 'index'])->name('political-parties.index');
Route::get('partidos/{id}', [PoliticalPartyController::class, 'show'])->name('political-parties.show');

Route::get('proposicoes', [LegislativeProposalController::class, 'index'])->name('legislative-proposals.index');
Route::get('proposicoes/{id}', [LegislativeProposalController::class, 'show'])->name('legislative-proposals.show');