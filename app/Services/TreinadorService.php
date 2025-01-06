<?php

namespace App\Services;

use App\Models\Treinador;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TreinadorService
{

    public function getTreinador(){
        $treinadores = Treinador::orderBy('id', 'ASC')->get();
        return [
            'status'=>true,
            'treinador' => $treinadores,
        ];
    }

    public function getBYId(Treinador $treinador){
        return[
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

    public function updateTreinador(array $data, Treinador $treinador)
    {
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

    public function deleteTreinador(Treinador $treinador){
        try{
            $deletedTreinador = $treinador->toArray();

            $treinador->delete();
            return[
                'status' => true,
                'treinador' => $deletedTreinador,
                'message' =>'excluido',
            ];
        }catch(Exception $e){
            return[
                'status' => true,
                'message' => 'excluido',
            ];
        }
    }
}
