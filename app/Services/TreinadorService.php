<?php

namespace App\Services;

use App\Models\Treinador;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class TreinadorService
{
    /**
     * Obtém uma lista de treinadores ordenada por ID
     *
     * @return array{status: bool, message: string, data: \Illuminate\Database\Eloquent\Collection}
     * @throws \Illuminate\Database\Exception Se houver falha ao recuperar os treinadores
     */
    public function getTreinador(): array
    {
        try {
            $treinadores = Treinador::orderBy('id', 'ASC')->get();

            $response = [
                'status' => true,
                'message' => 'Lista de treinadores encontrados',
                'data' => $treinadores,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Lista de treinadores não encontrada',
            ];
        }
        return $response;
    }

    /**
     * Obtém uma lista de treinadores com seus respectivos pokémons
     *
     * @return array{status: bool, message: string, data: \Illuminate\Database\Eloquent\Collection}
     * @throws \Illuminate\Database\Exception Se houver falha na busca.
     */
    public function getTreinadoresPokemons(): array
    {
        try {
            $treinadores = Treinador::with('pokemon')->get();

            $response = [
                'status' => true,
                'message' => 'Treinadores e pokémons encontrados',
                'data' => $treinadores,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Lista de treinadores e seus pokemons não encontrados',
            ];
        }
        return $response;
    }

    /**
     * Busca um treinador pelo ID
     *
     * @param int $id ID do treinador
     * @return array{status: bool, message: string, data: \App\Models\Treinador|null}
     * @throws ModelNotFoundException Se o treinador não for encontrado
     * @throws \Illuminate\Database\Exception Se houver um erro na busca
     */
    public function getById(int $id): array
    {
        try {
            $treinador = Treinador::findOrFail($id);

            $response = [
                'status' => true,
                'message' => 'Treinador encontrado',
                'data' => $treinador,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Treinador não encontrado',
            ];
        }
        return $response;
    }

    /**
     * Cadastra um novo treinador no banco de dados
     *
     * @param array $data Dados do treinador a ser cadastrado
     * @return array{status: bool, message: string, data: \App\Models\Treinador|null}
     * @throws \Illuminate\Database\Exception Se houver falha na inserção
     */
    public function storeTreinador(array $data): array
    {
        DB::beginTransaction();
        try {
            $treinador = Treinador::create($data);
            DB::commit();

            $response = [
                'status' => true,
                'message' => 'Treinador cadastrado',
                'data' => $treinador,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Treinador não cadastrado',
            ];
        }
        return $response;
    }

    /**
     * Atualiza os dados de um treinador existente
     *
     * @param array $data Dados atualizados do treinador
     * @param int $id ID do treinador a ser atualizado
     * @return array{status: bool, message: string, data: \App\Models\Treinador|null}
     * @throws ModelNotFoundException Se o treinador não for encontrado
     * @throws \Illuminate\Database\Exception Se houver falha na atualização
     */
    public function updateTreinador(array $data, int $id): array
    {
        DB::beginTransaction();
        try {
            $treinador = Treinador::findOrFail($id);
            $treinador->update($data);
            DB::commit();

            $response = [
                'status' => true,
                'message' => 'Treinador atualizado',
                'data' => $treinador,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Treinador não atualizado',
            ];
        }
        return $response;
    }

    /**
     * Remove um treinador do banco de dados
     *
     * @param int $id ID do treinador a ser removido
     * @return array{status: bool, message: string, data: \App\Models\Treinador|null}
     * @throws ModelNotFoundException Se o treinador não for encontrado
     * @throws \Illuminate\Database\Exception Se houver falha na remoção
     */
    public function deleteTreinador(int $id)
    {
        try {
            $treinador = Treinador::findOrFail($id);
            $treinador->delete();

            $response = [
                'status' => true,
                'message' => 'Treinador excluído',
                'data' => $treinador,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Treinador não excluído',
            ];
        }
        return $response;
    }
}
