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

    public function getTreinador()
    {
        try {
            $treinadores = Treinador::orderBy('id', 'ASC')->get();

            $response = [
                'status' => true,
                'message' => 'Lista treinadores encontrados',
                'data' => $treinadores

            ];
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Treinadores não encontrados',
            ];
        }

        return $response;
    }


    public function getTreinadoresPokemons()
    {
        try {
            $treinadores = Treinador::with('pokemon')->get();

            $response = [
                'status' => true,
                'message' => 'Treinadores e pokemons encontrados',
                'data' => $treinadores,
            ];
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'message' => "Lista de treinadores e pokemons não encontrada"
            ];
        }
        return $response;
    }

    public function getById($id)
    {
        $treinador = null;
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
        } catch (ModelNotFoundException | Exception $e) {
            return [
                'status' => false,
                'message' => 'Treinador não cadastrado',
            ];
        }
    }

    public function updateTreinador(array $data, $id)
    {
        $treinador = null;

        DB::beginTransaction();
        try {
            $treinador = Treinador::findOrFail($id);

            $treinador->update($data);
            DB::commit();

            $response = [
                'status' => true,
                'message' => 'Pokemon atualizado',
                'data' => $treinador,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Pokemon não atualizado',
            ];
        }

        return $response;
    }

    public function deleteTreinador($id)
    {
        $treinador = null;
        try {
            $treinador = Treinador::findOrFail($id);

            $treinador->delete();

            $response = [
                'status' => true,
                'message' => 'Treinador excluido',
                'data' => $treinador,
            ];
        } catch (ModelNotFoundException | Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Treinador não excluido',
            ];
        }
        return $response;
    }
}
