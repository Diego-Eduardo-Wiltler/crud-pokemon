<?php

namespace App\Http\Controllers;

use App\Models\Treinador;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TreinadorController extends Controller
{
    public function index(): JsonResponse
    {
        $treinadores = Treinador::orderBy('id', 'ASC')->get();
        return response()->json([
            'status' => true,
            'treinadores' => $treinadores,
        ], 200);
    }

    public function show(Treinador $id): JsonResponse
    {
        return response()->json([
            'status' => true,
            'treinador' => $id,
        ], 200);
    }
    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $treinador = Treinador::create([
                'nome' => $request->nome,
                'email' => $request->email,
                'regiao' => $request->regiao,
                'tipo_favorito' => $request->tipo_favorito,
                'idade' => $request->idade,
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'treinador' => $treinador,
                'message' => "cadastrado",
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'n criado',
            ], 400);
        }
    }
    public function update(Request $request, Treinador $id): JsonResponse{
        DB::beginTransaction();
        try{
            $id->update([
                'nome'=> $request ->nome,
                'email'=> $request ->email,
                'regiao'=> $request -> regiao,
                'tipo_favorito' => $request ->tipo_favorito,
                'idade' => $request ->idade,
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'treinador' => $id,
                'message' => "alterado",
            ],200);
        } catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => "não edtiado",
            ],400);
        }
    }
    public function destroy(Treinador $id):JsonResponse{
        try{
            $id->delete();

            return response()->json([
                'status' => true,
                'treinador' => $id,
                'message' => 'excluido',
            ],200);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message'=> 'n excluido',
            ],400);
        }
    }
}
