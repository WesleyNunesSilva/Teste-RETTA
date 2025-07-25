<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeputyController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/deputados', [DeputyController::class, 'index'])->name('deputies.index');
Route::get('/deputados/{id}', [DeputyController::class, 'show'])->name('deputies.show');
