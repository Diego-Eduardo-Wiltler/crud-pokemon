<?php

namespace App\Http\Controllers;

use App\Models\Treinador;
use App\Services\TreinadorService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TreinadorController extends Controller
{

    protected $treinadorService;

    public function __construct(TreinadorService $treinadorService)
    {
        $this->treinadorService = $treinadorService;
    }

    public function index(): JsonResponse
    {
        $result = $this->treinadorService->getTreinador();
        return response()->json($result, $result['status'] ? 201 : 400);
    }

    public function show($id): JsonResponse
    {
        $result = $this->treinadorService->getBYId($id);
        return response()->json($result, $result['status'] ? 200 : 400);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->only(['nome', 'email', 'regiao', 'tipo_favorito', 'idade']);
        $result = $this->treinadorService->storeTreinador($data);
        return response()->json($result, $result['status'] ? 201 : 400);
    }


    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->only(['nome', 'email', 'regiao', 'tipo_favorito', 'idade']);
        $result = $this->treinadorService->updateTreinador($data, $id);
        return response()->json($result, $result['status'] ? 201 : 400);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->treinadorService->deleteTreinador($id);
        return response()->json($result, $result['status'] ? 200 : 400);
    }
}
