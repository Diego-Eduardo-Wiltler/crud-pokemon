<?php

use App\Http\Controllers\PokemonController;
use App\Http\Controllers\TreinadorController;
use App\Models\Treinador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('treinadores', [TreinadorController::class, 'index']);
Route::get('treinadores/{id}', [TreinadorController::class, 'show']);
Route::post('treinadores', [TreinadorController::class,'store']);
Route::put('treinadores/{id}', [TreinadorController::class,'update']);
Route::delete('treinadores/{id}', [TreinadorController::class,'destroy']);

#Pokemon Rotas
Route::get('pokemons', [PokemonController::class, 'index']);
Route::get('pokemons/{id}', [PokemonController::class, 'show']);
Route::post('pokemons',[PokemonController::class, 'store']);
Route::put('pokemons/{id}', [PokemonController::class, 'update']);
Route::delete('pokemons/{id}', [PokemonController::class, 'destroy']);
