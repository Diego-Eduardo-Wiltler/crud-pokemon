<?php

use App\Http\Controllers\TreinadorController;
use App\Models\Treinador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('treinadores', [TreinadorController::class, 'index']);
Route::get('treinadores/{id}', [TreinadorController::class, 'show']);
Route::post('treinadores', [TreinadorController::class,'store']);
Route::put('treinadores/{id}', [TreinadorController::class,'update']);
Route::delete('treinadores/{id}', [TreinadorController::class,'destroy']);
