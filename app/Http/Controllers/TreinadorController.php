<?php

namespace App\Http\Controllers;

use App\Http\Requests\TreinadorStoreFormRequest;
use App\Http\Requests\TreinadorUpdateFormRequest;
use App\Http\Resources\PokemonResource;
use App\Http\Resources\TreinadorPokemonResource;
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

        if ($result['status']) {
            return response()->json(TreinadorResource::collection($result['treinador']));
        }
        return response()->json(['message' => $result['message']], 400);
    }

    public function show($id): JsonResponse
    {
        $result = $this->treinadorService->getBYId($id);

        if ($result['status']) {
            return response()->json(new TreinadorResource($result['treinador']));;
        }
        return response()->json(['message' => $result['message']], 400);
    }


    public function indexTreinadorPokemon(): JsonResponse
    {
        $result = $this->treinadorService->getTreinadoresPokemons();

        if ($result['status']) {
            return response()->json(TreinadorPokemonResource::collection($result['treinador']));
        }
        return response()->json(['message' => $result['message']], 400);
    }


    public function store(TreinadorStoreFormRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->treinadorService->storeTreinador($data);

        if ($result['status']) {
            return response()->json(new TreinadorResource($result['treinador']));
        }
        return response()->json(['message' => $result['message']], 400);
    }


    public function update(TreinadorUpdateFormRequest $request, $id): JsonResponse
    {
        $data = $request->validated();

        $result = $this->treinadorService->updateTreinador($data, $id);

        if ($result['status']) {
            return response()->json(new TreinadorResource($result['treinador']));
        }
        return response()->json(['message' => $result['message']], 400);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->treinadorService->deleteTreinador($id);

        if ($result['status']) {
            return response()->json(new TreinadorResource($result['treinador']));
        }
        return response()->json(['message' => $result['message']], 400);
    }
}
