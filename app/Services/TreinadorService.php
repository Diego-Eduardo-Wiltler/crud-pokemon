<?php

namespace App\Services;

use App\Models\Treinador;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TreinadorService
{

    public function getTreinador()
    {
        $treinadores = Treinador::orderBy('id', 'ASC')->get();
        return [
            'status' => true,
            'treinador' => $treinadores,
        ];
    }

    public function getById($id)
    {
        $treinador = Treinador::findOrFail($id);
        return [
            'status' => true,
            'treinador' => $treinador,
        ];
    }

    public function storeTreinador(array $data)
    {
        DB::beginTransaction();
        try {
            $treinador = Treinador::create($data);
            DB::commit();
            return [
                'status' => true,
                'treiandor' => $treinador,
                'message' => ' cadastrado',
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'n cadastrado',
            ];
        }
    }

    public function updateTreinador(array $data, $id)
    {
        $treinador = Treinador::findOrFail($id);
        DB::beginTransaction();
        try {
            $treinador->update($data);
            DB::commit();
            return [
                'status' => true,
                'treiandor' => $treinador,
                'message' => 'atualizado',
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'n atualizado',
            ];
        }
    }

    public function deleteTreinador($id)
    {
        $treinador = Treinador::findOrFail($id);
        try {
            $treinador->delete();
            return [
                'status' => true,
                'treinador' => $treinador,
                'message' => 'excluido',
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'excluido',
            ];
        }
    }
}
