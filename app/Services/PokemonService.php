<?php

namespace App\Services;

use App\Models\Pokemon;
use Exception;
use Illuminate\Support\Facades\DB;

class PokemonService
{
    public function getPokemon()
    {
        $pokemons = Pokemon::orderBy('id', 'Asc')->get();
        return [
            'status' => true,
            'pokemons' => $pokemons,
        ];
    }
    public function getById($id)
    {
        $pokemon = Pokemon::findOrFail($id);
        return [
            'status' => true,
            'pokemon' => $pokemon
        ];
    }

    public function storePokemon(array $data)
    {
        DB::beginTransaction();
        try {
            $pokemon = Pokemon::create($data);
            DB::commit();
            return [
                'status' => true,
                'pokemon' => $pokemon,
                'message' => 'cadastrado',
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'n cadastrado',
            ];
        }
    }

    public function updatePokemon(array $data, $id)
    {
        $pokemon = Pokemon::findOrFail($id);
        DB::beginTransaction();
        try {
            $pokemon->update($data);
            DB::commit();
            return [
                'status' => true,
                'pokemon' => $pokemon,
                'message' => 'atualizado',
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'n atualizado',
            ];
        }
    }

    public function deletePokemon($id)
    {
        $pokemon = Pokemon::findOrFail($id);
        try {
            $pokemon->delete($id);
            return [
                'status' => true,
                'pokemon' => $pokemon,
                'message' => 'excluido',
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'n excluido',
            ];
        }
    }
}
