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
            $pokemon1->vida_atual -= $pokemon2->ataque;
            $pokemon2->vida_atual -= $pokemon1->ataque;
        } while ($pokemon1->vida_atual > 0 && $pokemon2->vida_atual > 0);


        $pokemon1->save();
        $pokemon2->save();


        if ($pokemon1->vida_atual > 0 && $pokemon2->vida_atual <= 0) {
            $vencedor = $pokemon1;
        } elseif ($pokemon2->vida_atual > 0 && $pokemon1->vida_atual <= 0) {
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

    public function storeHealing($id)
    {
        $pokemon = Pokemon::find($id);

        $vidaRecuperada = $pokemon->vida - $pokemon->vida_atual;
        $pokemon->vida_atual = $pokemon->vida;
        $pokemon->save();

        return response()->json([
            'message' => 'pokemon curado',
            'status' => true,
            'pokemon' => [
                'nome' => $pokemon->nome,
                'vida_recuperada' => $vidaRecuperada,
                'vida_total' => $pokemon->vida,
            ],
        ]);
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
