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
        $result = $this->pokemonService->getTreinador();
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

    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->only(['nome' . 'tipo', 'peso', 'localizacao', 'shiny']);
        $result = $this->pokemonService->updatePokemon($data, $id);
        return response()->json($result, $result['status'] ? 200 : 400);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->pokemonService->deletePokemon($id);
        return response()->json($result, $result['status'] ? 200 : 400);
    }
}
