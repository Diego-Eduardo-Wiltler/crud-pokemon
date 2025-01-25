<?php

namespace App\Http\Controllers;

use App\Http\Requests\PokemonStoreFormRequest;
use App\Http\Requests\PokemonUpdateFormRequest;
use App\Http\Resources\PokemonBatalhaResource;
use App\Http\Resources\PokemonResource;
use App\Http\Resources\PokemonRoundBattleResource;
use App\Models\Pokemon;
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

        $status = $result['status'] ? 200 : 400;

        return response()->json(PokemonResource::collection($result['pokemons']), $status);
    }

    public function show($id): JsonResponse
    {
        $result = $this->pokemonService->getById($id);

        $status = $result['status'] ? 200 : 400;

        return response()->json(new PokemonResource($result['pokemon']), $status);
    }

    public function store(PokemonStoreFormRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->pokemonService->storePokemon($data);
        $status = $result['status'] ? 200 : 400;

        return response()->json(new PokemonResource($result['pokemon']), $status);
    }

    public function storeBattle(Request $request): JsonResponse
    {
        $id1 = $request->input('pokemon:id1');
        $id2 = $request->input('pokemon:id2');

        $result = $this->pokemonService->battlePokemon($id1, $id2);

        if ($result['status']) {
            return response()->json(new PokemonBatalhaResource($result['pokemon'], $result['message']));
        }
        return response()->json(['message' => $result['message']], 400);
    }

    public function storeRoundBattle(Request $request): JsonResponse
    {
        $id1 = $request->input('pokemon:id1');
        $id2 = $request->input('pokemon:id2');

        $result = $this->pokemonService->storeRound($id1, $id2);

        $status = $result['status'] ? 200 : 400;

        return response()->json(new PokemonRoundBattleResource($result), $status);
    }


    public function update(PokemonUpdateFormRequest $request, $id): JsonResponse
    {
        $data = $request->validated();

        $result = $this->pokemonService->updatePokemon($data, $id);

        $status = $result['status'] ? 200 : 400;

        return response()->json(new PokemonResource($result['pokemon']), $status);
    }

    public function storeLife(Request $request): JsonResponse
    {
        $id = $request->input('pokemon:id');
        $result = $this->pokemonService->storeHealing($id);
        return response()->json($result);
    }
    public function destroy($id): JsonResponse
    {
        $result = $this->pokemonService->deletePokemon($id);

        $status = $result['status' ? 200 : 400];

        return response()->json(new PokemonResource($result), $status);
    }
}
