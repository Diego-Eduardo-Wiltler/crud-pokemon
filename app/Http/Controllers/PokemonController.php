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
        $result = $this->pokemonService->getPokemons();

        if ($result['status']) {
            return $this->successResponse([
                PokemonResource::collection($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }

    public function show($id): JsonResponse
    {
        $result = $this->pokemonService->getById($id);

        if ($result['status']) {
            return $this->successResponse([
                new PokemonResource($result['data'])
            ]);
        }

        return $this->errorResponse($result['message']);
    }

    public function store(PokemonStoreFormRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->pokemonService->createPokemon($data);

        if ($result['status']) {
            return $this->successResponse([
                new PokemonResource($result['data'])
            ]);
        }

        return $this->errorResponse($request['message']);
    }



    public function storeBattle(Request $request): JsonResponse
    {
        $id1 = $request->input('pokemon:id1');
        $id2 = $request->input('pokemon:id2');

        $result = $this->pokemonService->battlePokemon($id1, $id2);
        if ($result['status']) {
            return $this->successResponse([
                'win_message' => $result['win_message'],
                'pokemon' => new PokemonBatalhaResource($result['data'])

            ]);
        }
        return $this->errorResponse($result['message']);
    }

    public function storeRoundBattle(Request $request): JsonResponse
    {
        $id1 = $request->input('pokemon:id1');
        $id2 = $request->input('pokemon:id2');

        $result = $this->pokemonService->executeRound($id1, $id2);

        if ($result['status']) {
            return $this->successResponse([
                'battle_message' => $result['battle_message'],
                'pokemons' => PokemonRoundBattleResource::collection($result['data']),
            ], $result['message']);
        }

        return $this->errorResponse($result['message']);
    }


    public function update(PokemonUpdateFormRequest $request, $id): JsonResponse
    {
        $data = $request->validated();

        $result = $this->pokemonService->updatePokemon($data, $id);

        if ($result['status']) {

            return $this->successResponse([
                new PokemonResource($result['data'])
            ]);
        }
        return $this->errorResponse($result['message']);
    }

    public function storeLife(Request $request): JsonResponse
    {
        $id = $request->input('pokemon:id');

        $result = $this->pokemonService->healPokemon($id);

        if ($result['status']) {
            return $this->successResponse([
                'life_recover' => $result['life_recover'],
                'pokemon' => new PokemonResource($result['data'])
            ]);
        }
       return $this->errorResponse($result['message']);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->pokemonService->deletePokemon($id);

        if ($result['status']) {
            return $this->successResponse([
                new PokemonResource($result['data'])
            ]);
        }

        return $this->errorResponse($result['message']);
    }
}
