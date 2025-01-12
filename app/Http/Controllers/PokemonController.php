<?php

namespace App\Http\Controllers;

use App\Services\PokemonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    protected $pokemonService;

    public function __construct(PokemonService $pokemonService)
    {
        $this->pokemonService = $pokemonService;
    }

    public function index(): JsonResponse
    {
        $result = $this->pokemonService->getPokemon();
        return response()->json($result, $result['status'] ? 200 : 400);
    }

    public function show($id): JsonResponse
    {
        $result = $this->pokemonService->getById($id);
        return response()->json($result, $result['status'] ? 200 : 400);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->only(['nome', 'tipo', 'peso', 'localizacao', 'shiny']);
        $result = $this->pokemonService->storePokemon($data);
        return response()->json($result, $result['status'] ? 201 : 400);
    }

    public function storeBattle(Request $request) : JsonResponse
    {
        $id1 = $request->input('pokemon:id1');
        $id2 = $request->input('pokemon:id2');
        $result = $this->pokemonService->battlePokemon($id1,$id2);
        return response()->json($result);
    }


    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->only(['nome' . 'tipo', 'peso', 'localizacao', 'shiny']);
        $result = $this->pokemonService->updatePokemon($data, $id);
        return response()->json($result, $result['status'] ? 200 : 400);
    }

    public function storeLife(Request $request): JsonResponse{
        $id = $request->input('pokemon:id');
        $result = $this->pokemonService->storeHealing($id);
        return response()->json($result);
    }
    public function destroy($id): JsonResponse
    {
        $result = $this->pokemonService->deletePokemon($id);
        return response()->json($result, $result['status'] ? 200 : 400);
    }
}
