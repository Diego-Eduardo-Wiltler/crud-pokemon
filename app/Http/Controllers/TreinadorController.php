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

        $status = $result['status'] ? 200 : 400;

        return response()->json(TreinadorResource::collection($result['treinador']), $status);
    }

    public function indexTreinadorPokemon(): JsonResponse
    {
        $result = $this->treinadorService->getTreinadoresPokemons();

        $status = $result['status'] ? 200 : 400;

        return response()->json(TreinadorPokemonResource::collection($result['treinador']), $status);
    }

    public function show($id): JsonResponse
    {
        $result = $this->treinadorService->getBYId($id);

        $status = $result['status'] ? 200 : 400;

        return response()->json(new TreinadorResource($result['treinador']), $status);
    }




    public function store(TreinadorStoreFormRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->treinadorService->storeTreinador($data);

        $status = $result['status'] ? 200 : 400;

        return response()->json(new TreinadorResource($result['treiandor']), $status);
    }


    public function update(TreinadorUpdateFormRequest $request, $id): JsonResponse
    {
        $data = $request->validated();

        $result = $this->treinadorService->updateTreinador($data, $id);


        return response()->json(new TreinadorResource($result['treiandor']),200);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->treinadorService->deleteTreinador($id);

        $status = $result['status' ? 200 : 400];

        return response()->json(new TreinadorResource($result['treinador']),$status);
    }
}
