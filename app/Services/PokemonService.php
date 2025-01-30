<?php

namespace App\Services;

use App\Models\Pokemon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PokemonService
{
    public function getPokemons()
    {
        try {
            $pokemons = Pokemon::orderBy('id', 'Asc')->get();

            $response = [
                'status' => true,
                'message' => 'Listando pokemons',
                'data' => $pokemons,
            ];
        } catch (Exception $e) {
            $response = [
                'status' => true,
                'message' => 'Não foi possivel listar pokemons',

            ];
        }
        return $response;
    }
    public function getById($id)
    {
        try {
            $pokemon = Pokemon::findOrFail($id);
            $response = [
                'status' => true,
                'message' => 'Listando pokemons',
                'data' => $pokemon,
            ];
        } catch (ModelNotFoundException | Exception $e) {

            $response = [
                'status' => true,
                'message' => 'Não foi possivel listar pokemon',
            ];
        }

        return $response;
    }

    public function createPokemon(array $data)
    {
        DB::beginTransaction();
        try {
            $pokemon = Pokemon::create($data);
            DB::commit();
            $response = [
                'status' => true,
                'message' => 'Pokemon foi cadastrado',
                'data' => $pokemon,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Pokemon não cadastrado',
            ];
        }
        return $response;
    }

    public function battlePokemon($id1, $id2)
    {

        $pokemon1 = null;
        $pokemon2 = null;

        try {

            $pokemon1 = Pokemon::findOrFail($id1);
            $pokemon2 = Pokemon::findOrFail($id2);


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
                    "status" => false,
                    "message" => "A batalha terminou em empate!",
                ];
            }

            $response = [
                "status" => true,
                "win_message" => 'O pokemon vencendor é',
                "data" => $vencedor
            ];
        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Produto não encontrado',
            ];
        }
        return $response;
    }

    public function healPokemon($id)
    {
        try {
            $pokemon = Pokemon::findOrFail($id);

            $vidaRecuperada = $pokemon->vida - $pokemon->vida_atual;
            $pokemon->vida_atual = $pokemon->vida;
            $pokemon->save();

            $response = [
                'message' => 'pokemon curado',
                'status' => true,
                'life_recover' => $vidaRecuperada,
                'data' =>  $pokemon,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => true,
                'message' => 'Pokemon não curado',

            ];
        }

        return $response;
    }

    public function executeRound($id1, $id2)
    {
        $attacker = null;
        $deffender = null;
        try {
            $attacker = Pokemon::findOrFail($id1);
            $deffender = Pokemon::findOrFail($id2);

            $defenseMitigation = $deffender->defesa / $attacker->ataque * 100;
            $damageDealt = $attacker->ataque;
            $defesaTexto = '';

            if ($defenseMitigation < 30) {
                $defesaTexto = ' não conseguiu se defender do ataque ';
            } elseif ($defenseMitigation >= 30 && $defenseMitigation < 50) {
                $damageDealt /= 1.2;
                $defesaTexto = ' se defendeu um pouco do ataque ';
            } elseif ($defenseMitigation >= 50 && $defenseMitigation < 100) {
                $damageDealt /= 1.5;
                $defesaTexto = ' se defendeu bem do ataque ';
            } elseif ($defenseMitigation == 100) {
                $damageDealt /= 2;
                $defesaTexto = ' se defendeu efetivamente do ataque ';
            } elseif ($defenseMitigation >= 130) {
                $damageDealt /= 3;
                $defesaTexto = ' se defendeu extremamente bem do ataque ';
            } else {
                return [
                    'status' => false,
                    'message' => [
                        'Algo deu errado durante a batalha. Verifique os valores.',
                    ],
                ];
            }
            $damageDealt = ceil($damageDealt);
            $deffender->vida_atual -= $damageDealt;
            $deffender->save();

            $battle_message = [
                $attacker->nome . ' atacou com ' . $attacker->ataque . ' de dano',
                $deffender->nome .  $defesaTexto,
                $attacker->nome . ' causou ' . $damageDealt  . ' de dano ',
                $deffender->nome . ' ainda se mantém na luta ',
                $deffender->nome . ' ainda possui ' . $deffender->vida_atual . ' pontos de vida',

    ];
            $pokemons = [
                $attacker,
                $deffender,
            ];

            $response = [
                'status' => true,
                'message' => 'Batalha iniciada',
                'battle_message' => $battle_message,
                'data' => $pokemons,

            ];
        } catch (Exception $e) {

            $response = [
                'status' => false,
                'message' => 'Batalha não ocorreu',
            ];
        }
        return $response;
    }


    public function updatePokemon(array $data, $id)
    {
        $pokemon = null;
        DB::beginTransaction();

        try {
            $pokemon = Pokemon::findOrFail($id);
            $pokemon->update($data);

            DB::commit();

            $response = [
                'status' => true,
                'message' => 'Pokemon atualizado',
                'data' => $pokemon,
            ];
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Pokemon não atualizado',
            ];
        }
        return $response;
    }

    public function deletePokemon($id)
    {

        $pokemon = null;

        try {
            $pokemon = Pokemon::findOrFail($id);

            $pokemon->delete($id);

            $response = [
                'status' => true,
                'message' => 'Pokemon excluído',
                'data' => $pokemon,
            ];
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Pokemon não excluido',
            ];
        }
        return $response;
    }
}
