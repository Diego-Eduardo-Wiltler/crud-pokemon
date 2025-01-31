<?php

namespace App\Services;

use App\Models\Pokemon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PokemonService
{
    /**
     * Retorna a mesma entrada fornecida como saída.
     *
     * @param int|bool $input Entrada numérica ou booleana.
     * @return int|bool Retorna o mesmo valor de entrada.
     */
    public function foo($input)
    {
        return $input;
    }

    /**
     * Obtém uma lista de pokémons ordenada por ID.
     *
     * @return array{status: bool, message: string, data: \Illuminate\Database\Eloquent\Collection|null}
     * @throws Exception Se houver falha ao listar os pokémons.
     */
    public function getPokemons()
    {
        try {
            $pokemons = Pokemon::orderBy('id', 'Asc')->get();

            $response = [
                'status' => true,
                'message' => 'Listando pokémons',
                'data' => $pokemons,
            ];
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Não foi possível listar pokémons',
            ];
        }
        return $response;
    }

    /**
     * Obtém um pokémon pelo ID.
     *
     * @param int $id ID do pokémon.
     * @return array{status: bool, message: string, data: \App\Models\Pokemon|null}
     * @throws ModelNotFoundException Se o pokémon não for encontrado.
     * @throws Exception Se houver falha ao buscar o pokémon.
     */
    public function getById($id)
    {
        try {
            $pokemon = Pokemon::findOrFail($id);
            $response = [
                'status' => true,
                'message' => 'Pokémon encontrado',
                'data' => $pokemon,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Não foi possível encontrar o pokémon',
            ];
        }

        return $response;
    }

    /**
     * Cria um novo pokémon.
     *
     * @param array $data Dados do pokémon a ser criado
     * @return array{status: bool, message: string, data: \App\Models\Pokemon|null}
     * @throws Exception Se houver falha ao criar o pokémon.
     */
    public function createPokemon(array $data)
    {
        DB::beginTransaction();
        try {
            $pokemon = Pokemon::create($data);
            DB::commit();
            $response = [
                'status' => true,
                'message' => 'Pokémon cadastrado',
                'data' => $pokemon,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            DB::rollBack();
            $response = [
                'status' => false,
                'message' => 'Pokémon não cadastrado',
            ];
        }
        return $response;
    }

    /**
     * Realiza uma batalha entre dois pokémons
     *
     * @param int $id1 ID do primeiro pokémon.
     * @param int $id2 ID do segundo pokémon.
     * @return array{status: bool, message: string, data: \App\Models\Pokemon|null}
     * @throws ModelNotFoundException Se um dos pokémons não for encontrado.
     * @throws Exception Se houver falha durante a batalha.
     */
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
                "win_message" => 'O pokémon vencedor é',
                "data" => $vencedor
            ];
        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Pokémon não encontrado',
            ];
        }
        return $response;
    }

    /**
     * Cura um pokémon, restaurando sua vida atual para o valor máximo
     *
     * @param int $id ID do pokémon.
     * @return array{status: bool, message: string, life_recover: int, data: \App\Models\Pokemon|null}
     * @throws ModelNotFoundException Se o pokémon não for encontrado.
     * @throws Exception Se houver falha ao curar o pokémon.
     */
    public function healPokemon($id)
    {
        try {
            $pokemon = Pokemon::findOrFail($id);

            $vidaRecuperada = $pokemon->vida - $pokemon->vida_atual;
            $pokemon->vida_atual = $pokemon->vida;
            $pokemon->save();

            $response = [
                'message' => 'Pokémon curado',
                'status' => true,
                'life_recover' => $vidaRecuperada,
                'data' => $pokemon,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Pokémon não curado',
            ];
        }

        return $response;
    }

    /**
     * Executa um round de batalha entre dois pokémons
     *
     * @param int $id1 ID do primeiro pokémon
     * @param int $id2 ID do segundo pokémon
     * @return array{status: bool, message: string, battle_message: array, data: array|null}
     * @throws ModelNotFoundException Se um dos pokémons não for encontrado
     * @throws Exception Se houver falha durante o round
     */
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
                $deffender->nome . $defesaTexto,
                $attacker->nome . ' causou ' . $damageDealt . ' de dano ',
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
        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Batalha não iniciada',
            ];
        }
        return $response;
    }

    /**
     * Atualiza os dados de um pokémon.
     *
     * @param array $data Dados atualizados do pokémon
     * @param int $id ID do pokémon a ser atualizado
     * @return array{status: bool, message: string, data: \App\Models\Pokemon|null}
     * @throws ModelNotFoundException Se o pokémon não for encontrado
     * @throws Exception Se houver falha ao atualizar o pokémon
     */
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
                'message' => 'Pokémon atualizado',
                'data' => $pokemon,
            ];

        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Pokémon não atualizado',
            ];
        }
        return $response;
    }

    /**
     * Exclui um pokémon.
     *
     * @param int $id ID do pokémon a ser excluído
     * @return array{status: bool, message: string, data: \App\Models\Pokemon|null}
     * @throws ModelNotFoundException Se o pokémon não for encontrado
     * @throws Exception Se houver falha ao excluir o pokémon
     */
    public function deletePokemon($id)
    {
        $pokemon = null;

        try {
            $pokemon = Pokemon::findOrFail($id);
            $pokemon->delete();

            $response = [
                'status' => true,
                'message' => 'Pokémon excluído',
                'data' => $pokemon,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Pokémon não excluído',
            ];
        }
        return $response;
    }
}
