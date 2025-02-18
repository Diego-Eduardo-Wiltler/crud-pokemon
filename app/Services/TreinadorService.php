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
