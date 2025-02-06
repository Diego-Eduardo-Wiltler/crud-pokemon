<?php

namespace App\Services;

use App\Models\Treinador;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class TreinadorService
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
     * Obtém uma lista de treinadores ordenada por ID
     *
     * @return array{message: string, data: \Illuminate\Database\Eloquent\Collection}
     */
    public function getTreinador(): array
    {
        $treinadores = Treinador::orderBy('id', 'ASC')->get();
        $response = [
            'message' => 'Lista de treinadores encontrados',
            'data' => $treinadores,
        ];

        return $response;
    }

    /**
     * Obtém uma lista de treinadores com seus respectivos pokémons
     *
     * @return array{message: string, data: \Illuminate\Database\Eloquent\Collection}
     */
    public function getTreinadoresPokemons(): array
    {
        $treinadores = Treinador::with('pokemon')->get();
        $response = [
            'message' => 'Treinadores e pokémons encontrados',
            'data' => $treinadores,
        ];

        return $response;
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
        $response = [
            'message' => 'Treinador encontrado',
            'data' => $treinador,
        ];
        return $response;
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
        $response = [
            'message' => 'Treinador cadastrado',
            'data' => $treinador,
        ];

        return $response;
    }

    /**
     * Atualiza os dados de um treinador existente
     *
     * @param array $data Dados atualizados do treinador
     * @param int $id ID do treinador a ser atualizado
     * @return array{message: string, data: \App\Models\Treinador|null}
     */
    public function updateTreinador(array $data, int $id): array
    {
        DB::beginTransaction();
        $treinador = Treinador::findOrFail($id);
        $treinador->update($data);
        DB::commit();
        $response = [
            'message' => 'Treinador atualizado',
            'data' => $treinador,
        ];

        return $response;
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
        $response = [
            'message' => 'Treinador excluído',
            'data' => $treinador,
        ];

        return $response;
    }
}
