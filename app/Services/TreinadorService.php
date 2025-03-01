<?php

namespace App\Services;

use App\Models\Pokemon;
use App\Models\Treinador;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class TreinadorService
{
    /**
     * Obtém uma lista de treinadores ordenada por ID
     *
     * @return array{message: string, data: \Illuminate\Database\Eloquent\Collection}
     */
    public function getTreinador(): array
    {
        $treinadores = Treinador::orderBy('id', 'ASC')->get();

        return [
            'message' => 'Lista de treinadores encontrados',
            'data' => $treinadores,
        ];
    }

    /**
     * Obtém uma lista de treinadores com seus respectivos pokémons
     *
     * @return array{message: string, data: \Illuminate\Database\Eloquent\Collection}
     */
    public function getTreinadoresPokemons(): array
    {
        $treinadores = Treinador::with('pokemon')->get();

        return [
            'message' => 'Treinadores e pokémons encontrados',
            'data' => $treinadores,
        ];
    }

    /**
     * Busca um treinador pelo ID
     *
     * @param int $id ID do treinador
     * @return array{message: string, data: \App\Models\Treinador|null}
     */
    public function getById(int $id): array
    {
        $treinador = Treinador::findOrFail($id);

        return [
            'message' => 'Treinador encontrado',
            'data' => $treinador,
        ];
    }

    /**
     * Cadastra um novo treinador no banco de dados
     *
     * @param array $data Dados do treinador a ser cadastrado
     * @return array{message: string, data: \App\Models\Treinador|null}
     */
    public function storeTreinador(array $data): array
    {
        DB::beginTransaction();

        $treinador = Treinador::create($data);

        DB::commit();

        return [
            'message' => 'Treinador cadastrado',
            'data' => $treinador,
        ];
    }

    public function storeTreinadorTrade($id1, $id2)
    {
        $treinador1 = Treinador::findOrFail($id1);
        $treinador2 = Treinador::findOrFail($id2);

        $pokemon1 = $treinador1->pokemon_id;
        $pokemon2 = $treinador2->pokemon_id;

        $treinador1->pokemon_id = $pokemon2;
        $treinador2->pokemon_id = $pokemon1;

        $treinador1->save();
        $treinador2->save();

        $treinadores = [
            $treinador1,
            $treinador2,
        ];

        return [
            // 'trade_message' => $trade_message,
            'data' => $treinadores
        ];
    }

    /**
     * Atualiza os dados de um treinador existente
     *
     * @param array $data Dados atualizados do treinador
     * @param int $id ID do treinador a ser atualizado
     * @return array{message: string, data: \App\Models\Treinador|null}
     */
    public function updateTreinador(int $id, array $data): array
    {
        DB::beginTransaction();

        $treinador = Treinador::findOrFail($id);
        $treinador->update($data);

        DB::commit();

        return  [
            'message' => 'Treinador atualizado',
            'data' => $treinador,
        ];
    }

    /**
     * Remove um treinador do banco de dados
     *
     * @param int $id ID do treinador a ser removido
     * @return array{message: string, data: \App\Models\Treinador|null}
     */
    public function deleteTreinador(int $id)
    {
        $treinador = Treinador::findOrFail($id);
        $treinador->delete();

        return [
            'message' => 'Treinador excluído',
            'data' => $treinador,
        ];
    }
}
