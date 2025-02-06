<?php

namespace App\Services;

use App\Models\Pokemon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PokemonService
{
    /**
     * Retorna a mesma entrada fornecida como saída
     *
     * @param int|bool $input Entrada numérica ou booleana
     * @return int|bool Retorna o mesmo valor de entrada
     */
    public function foo($input)
    {
        return $input;
    }

    /**
     * Obtém uma lista de pokémons ordenada por ID
     *
     * @return array{message: string, data: \Illuminate\Database\Eloquent\Collection|null}
     */
    public function getPokemons()
    {
        $pokemons = Pokemon::orderBy('id', 'Asc')->get();
        $response = [
            'message' => 'Listando pokémons',
            'data' => $pokemons,
        ];

        return $response;
    }

    /**
     * Obtém um pokémon pelo ID.
     *
     * @param int $id ID do pokémon.
     * @return array{message: string, data: \App\Models\Pokemon|null}
     */
    public function getById($id)
    {
        $pokemon = Pokemon::findOrFail($id);
        $response = [
            'message' => 'Pokémon encontrado',
            'data' => $pokemon,
        ];

        return $response;
    }

    /**
     * Cria um novo pokémon.
     *
     * @param array $data Dados do pokémon a ser criado
     * @return array{message: string, data: \App\Models\Pokemon|null}
     */
    public function createPokemon(array $data)
    {
        DB::beginTransaction();
        $pokemon = Pokemon::create($data);
        DB::commit();
        $response = [
            'message' => 'Pokémon cadastrado',
            'data' => $pokemon,
        ];

        return $response;
    }

    /**
     * Realiza uma batalha entre dois pokémons
     *
     * @param int $id1 ID do primeiro pokémon.
     * @param int $id2 ID do segundo pokémon.
     * @return array{message: string, data: \App\Models\Pokemon|null}
     */
    public function battlePokemon($id1, $id2)
    {
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
                "message" => "A batalha terminou em empate!",
            ];
        }
        $response = [
            "win_message" => 'O pokémon vencedor é',
            "data" => $vencedor
        ];

        return $response;
    }

    /**
     * Cura um pokémon, restaurando sua vida atual para o valor máximo
     *
     * @param int $id ID do pokémon
     * @return array{message: string, life_recover: int, data: \App\Models\Pokemon|null}
     */
    public function healPokemon($id)
    {
        $pokemon = Pokemon::findOrFail($id);
        $vidaRecuperada = $pokemon->vida - $pokemon->vida_atual;
        $pokemon->vida_atual = $pokemon->vida;
        $pokemon->save();
        $response = [
            'message' => 'Pokémon curado',
            'life_recover' => $vidaRecuperada,
            'data' => $pokemon,
        ];

        return $response;
    }

    /**
     * Executa um round de batalha entre dois pokémons
     *
     * @param int $id1 ID do primeiro pokémon
     * @param int $id2 ID do segundo pokémon
     * @return array{message: string, battle_message: array, data: array|null}
     */
    public function executeRound($id1, $id2)
    {
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
            'message' => 'Batalha iniciada',
            'battle_message' => $battle_message,
            'data' => $pokemons,
        ];

        return $response;
    }

    /**
     * Atualiza os dados de um pokémon.
     *
     * @param array $data Dados atualizados do pokémon
     * @param int $id ID do pokémon a ser atualizado
     * @return array{message: string, data: \App\Models\Pokemon|null}
     */
    public function updatePokemon(array $data, $id)
    {
        DB::beginTransaction();
        $pokemon = Pokemon::findOrFail($id);
        $pokemon->update($data);
        DB::commit();
        $response = [
            'message' => 'Pokémon atualizado',
            'data' => $pokemon,
        ];

        return $response;
    }

    /**
     * Exclui um pokémon.
     *
     * @param int $id ID do pokémon a ser excluído
     * @return array{message: string, data: \App\Models\Pokemon|null}
     * @throws Exception Se houver falha ao excluir o pokémon
     */
    public function deletePokemon($id)
    {
        $pokemon = Pokemon::findOrFail($id);
        $pokemon->delete();
        $response = [
            'message' => 'Pokémon excluído',
            'data' => $pokemon,
        ];

        return $response;
    }
}
