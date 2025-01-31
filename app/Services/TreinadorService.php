<?php

namespace App\Services;

use App\Models\Pokemon;
use App\Models\Treinador;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TreinadorService
{
    /**
     * Retorna a saída da entrada fornecida
     *
     * @param int|bool $input Entrada como valor inteiro ou booleano
     * @return int|bool Saída praticamente igual à entrada
     */
    public function foo($input)
    {
        return $input;
    }

    /**
     * Obtém uma lista de treinadores ordenada por ID
     *
     * @return array Retorna um array com status, mensagem e a lista de treinadores
     * @throws Exception Se houver falha ao recuperar os treinadores
     */
    public function getTreinador()
    {
        try {
            $treinadores = Treinador::orderBy('id', 'ASC')->get();

            return [
                'status' => true,
                'message' => 'Lista de treinadores encontrados',
                'data' => $treinadores,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Treinadores não encontrados',
            ];
        }
    }

    /**
     * Obtém uma lista de treinadores com seus respectivos pokémons.
     *
     * @return array Retorna um array com status, mensagem e a lista de treinadores e seus pokémons.
     * @throws Exception Se houver falha na busca.
     */
    public function getTreinadoresPokemons()
    {
        try {
            $treinadores = Treinador::with('pokemon')->get();

            return [
                'status' => true,
                'message' => 'Treinadores e pokémons encontrados',
                'data' => $treinadores,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Lista de treinadores e pokémons não encontrada',
            ];
        }
    }

      /**
     * Busca um treinador pelo ID
     *
     * @param int $id ID do treinador
     * @return array Retorna um array com status, mensagem e os dados do treinador
     * @throws ModelNotFoundException Se o treinador não for encontrado
     * @throws Exception Se houver um erro na busca
     */
    public function getById($id)
    {
        try {
            $treinador = Treinador::findOrFail($id);

            return [
                'status' => true,
                'message' => 'Treinador encontrado',
                'data' => $treinador,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            return [
                'status' => false,
                'message' => 'Treinador não encontrado',
            ];
        }
    }

   /**
     * Cadastra um novo treinador no banco de dados
     *
     * @param array $data Dados do treinador a ser cadastrado
     * @return array Retorna um array com status, mensagem e os dados do treinador cadastrado
     * @throws Exception Se houver falha na inserção
     */
    public function storeTreinador(array $data)
    {
        DB::beginTransaction();
        try {
            $treinador = Treinador::create($data);
            DB::commit();

            return [
                'status' => true,
                'message' => 'Treinador cadastrado',
                'data' => $treinador,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Treinador não cadastrado',
            ];
        }
    }

     /**
     * Atualiza os dados de um treinador existente
     *
     * @param array $data Dados atualizados do treinador
     * @param int $id ID do treinador a ser atualizado
     * @return array Retorna um array com status, mensagem e os dados do treinador atualizado
     * @throws ModelNotFoundException Se o treinador não for encontrado
     * @throws Exception Se houver falha na atualização
     */
    public function updateTreinador(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $treinador = Treinador::findOrFail($id);
            $treinador->update($data);
            DB::commit();

            return [
                'status' => true,
                'message' => 'Treinador atualizado',
                'data' => $treinador,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Treinador não atualizado',
            ];
        }
    }

      /**
     * Remove um treinador do banco de dados
     *
     * @param int $id ID do treinador a ser removido
     * @return array Retorna um array com status e mensagem de sucesso ou falha
     * @throws ModelNotFoundException Se o treinador não for encontrado
     * @throws Exception Se houver falha na remoção
     */
    public function deleteTreinador($id)
    {
        try {
            $treinador = Treinador::findOrFail($id);
            $treinador->delete();

            return [
                'status' => true,
                'message' => 'Treinador excluído',
                'data' => $treinador,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            return [
                'status' => false,
                'message' => 'Treinador não excluído',
            ];
        }
    }
}
