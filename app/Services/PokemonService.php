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

    public function battlePokemon($id1, $id2)
    {
        $pokemon1 = Pokemon::find($id1);
        $pokemon2 = Pokemon::find($id2);


        do {
            $pokemon1->vida -= $pokemon2->ataque;
            $pokemon2->vida -= $pokemon1->ataque;
        } while ($pokemon1->vida > 0 && $pokemon2->vida > 0);


        $pokemon1->save();
        $pokemon2->save();


        if ($pokemon1->vida > 0 && $pokemon2->vida <= 0) {
            $vencedor = $pokemon1;
        } elseif ($pokemon2->vida > 0 && $pokemon1->vida <= 0) {
            $vencedor = $pokemon2;
        } else {
            return [
                "message" => "A batalha terminou em empate!",
                "status" => false,
            ];
        }


        return [
            "message" => "O pokemon vencedor é",
            "status" => true,
            "pokemon" => [
                'nome' => $vencedor->nome,
                'tipo' => $vencedor->tipo,
                'localizacao' => $vencedor->localizacao,
                'shiny' => $vencedor->shiny,
            ],
        ];
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
