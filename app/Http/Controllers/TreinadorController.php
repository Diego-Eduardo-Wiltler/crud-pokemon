<?php

namespace App\Http\Controllers;

use App\Http\Resources\PokemonResource;
use App\Http\Resources\TreinadorResource;
use App\Services\TreinadorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        return response()->json($result, $result['status'] ? 200 : 400);
    }

    public function show($id): JsonResponse
    {
        $result = $this->treinadorService->getBYId($id);

        $status = $result['status' ? 200 : 400];

        return response()->json(new TreinadorResource($result), $status);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->only(['nome', 'email', 'regiao', 'tipo_favorito', 'idade', 'pokemon_id']);

        $result = $this->treinadorService->storeTreinador($data);

        $status = $result['status' ? 200 : 400];

        return response()->json(new TreinadorResource($result), $status);
    }


    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->only(['nome', 'email', 'regiao', 'tipo_favorito', 'idade', 'pokemon_id']);

        $result = $this->treinadorService->updateTreinador($data, $id);

        $status = $result['status' ? 200 : 400];

        return response()->json(new TreinadorResource($result), $status);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->treinadorService->deleteTreinador($id);

        $status = $result['status' ? 200 : 400];

        return response()->json(new TreinadorResource($result),$status);
    }
}
