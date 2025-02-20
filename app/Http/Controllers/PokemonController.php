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


    /**
     * GET /pokemons
     *
     * Retorna lista de pokemons cadastrados
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: array<PokemonResource>
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     */

    public function index(): JsonResponse
    {
        $result = $this->pokemonService->getPokemons();

        return $this->successResponse([
            PokemonResource::collection($result['data'])
        ]);
    }

    /**
     * GET /pokemons/{id}
     *
     * Retorna um unico pokemon pelo id
     *
     * @urlParam id int required ID do pokemon a ser encontrado. Example: 1
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: PokemonResource
     * }
     *
     *  @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param $id ID do pokemon a ser encontrado
     *
     */
    public function show($id): JsonResponse
    {
        $result = $this->pokemonService->getById($id);

        return $this->successResponse([
            new PokemonResource($result['data'])
        ]);
    }

    /**
     * POST /pokemons
     *
     * Cadastra um novo pokemon
     *
     * @bodyParam nome string required Nome do pokemon. Example: Caterpie
     * @bodyParam ataque int required Valor de ataque do pokemon. Example: 5
     * @bodyParam defesa int required Valor de defesa do pokemon. Example: 5
     * @bodyParam vida int required Valor total de vida do pokemon. Example: 45
     * @bodyParam vida_atual int required Valor atual de vida do pokemon. Example: 50
     * @bodyParam tipo string required Tipo do pokemon. Example: Inseto
     * @bodyParam peso string required Peso do pokemon. Example: 2.9Kg
     * @bodyParam localizacao string required Localização onde o pokemon pode ser encontrado. Example: Floresta Selvagem - Grama Alta
     * @bodyParam shiny int required Indica se o pokemon é shiny (0 para não, 1 para sim). Example: 0
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: PokemonResource
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param PokemonStoreFormRequest $request Requisição contendo os dados do pokemon.
     * @return JsonResponse
     *
     *
     */

    public function store(PokemonStoreFormRequest $request): JsonResponse
    {
        $data = $request->validated();
        $result = $this->pokemonService->createPokemon($data);

        return $this->successResponse([
            new PokemonResource($result['data'])
        ]);
    }

    /**
     * POST pokemons/battle
     *
     * Realiza a luta completa de dois pokemons
     *
     * @bodyParam pokemon:id1 int required id do pokemon. Example: 1
     * @bodyParam pokemon:id2 int required id do pokemon. Example: 2
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: PokemonResource
     * }
     *
     * @param Request $request Requisição contendo os dados do pokemon.
     * @return JsonResponse
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     */

    public function storeBattle(Request $request): JsonResponse
    {
        $id1 = $request->input('pokemon:id1');
        $id2 = $request->input('pokemon:id2');
        $result = $this->pokemonService->battlePokemon($id1, $id2);

        return $this->successResponse([
            'win_message' => $result['win_message'],
            'pokemon' => new PokemonBatalhaResource($result['data'])

        ]);
    }

    /**
     * POST pokemons/round
     *
     * Realiza a batalha de um turno entre dois pokemons, um atacante e um defensor
     *
     * @bodyParam pokemon:id1 int required id do pokemon. Example: 1
     * @bodyParam pokemon:id2 int required id do pokemon. Example: 2
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: PokemonResource
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param Request $request Requisição contendo os dados do pokemon
     * @param int $id1 ID do pokemon atacante
     * @param int $id2 ID do pokemon defensor
     * @return JsonResponse
     *
     *
     */

    public function storeRoundBattle(Request $request): JsonResponse
    {
        $id1 = $request->input('pokemon:id1');
        $id2 = $request->input('pokemon:id2');
        $result = $this->pokemonService->executeRound($id1, $id2);

        return $this->successResponse([
            'battle_message' => $result['battle_message'],
            'pokemons' => PokemonRoundBattleResource::collection($result['data']),
        ], $result['message']);
    }

    /**
     * PUT /pokemons/{id}
     *
     * Atualiza os dados de um pokemon existente
     *
     * @urlParam id int required ID do pokemon a ser atualizado. Example: 1
     *
     * @bodyParam nome string Nome do pokemon. Example: Caterpie
     * @bodyParam ataque int Valor de ataque do pokemon. Example: 5
     * @bodyParam defesa int Valor de defesa do pokemon. Example: 5
     * @bodyParam vida int Valor total de vida do pokemon. Example: 45
     * @bodyParam vida_atual int Valor atual de vida do pokemon. Example: 50
     * @bodyParam tipo string Tipo do pokemon. Example: Inseto
     * @bodyParam peso string Peso do pokemon. Example: 2.9Kg
     * @bodyParam localizacao string Localização onde o pokemon pode ser encontrado. Example: Floresta Selvagem - Grama Alta
     * @bodyParam shiny int Indica se o pokemon é shiny (0 para não, 1 para sim). Example: 0
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: PokemonResource
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string,
     *
     * }
     *
     * @param PokemonUpdateFormRequest $request Requisição contendo os dados atualizados do pokemon.
     * @param int $id ID do pokemon a ser atualizado
     * @return JsonResponse
     */

    public function update(PokemonUpdateFormRequest $request, $id): JsonResponse
    {
        $data = $request->validated();
        $result = $this->pokemonService->updatePokemon($id, $data);

        return $this->successResponse([
            new PokemonResource($result['data'])
        ]);
    }

    /**
     * POST pokemons/healing
     *
     * Realiza a cura de um pokemon
     *
     * @bodyParam pokemon:id int required id do pokemon. Example: 1
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: PokemonResource
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param Request $request Requisição contendo os dados do pokemon
     * @param int $id ID do pokemon a ser curado
     * @return JsonResponse
     *
     *
     */

    public function storeLife(Request $request): JsonResponse
    {
        $id = $request->input('pokemon:id');
        $result = $this->pokemonService->healPokemon($id);

        return $this->successResponse([
            'life_recover' => $result['life_recover'],
            'pokemon' => new PokemonResource($result['data'])
        ]);
    }

    /**
     * DELETE /pokemons/{id}
     *
     * Remove um pokemon existente
     *
     * @urlParam id int required ID do pokemon a ser removido. Example: 1
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: PokemonResource
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param int $id ID do pokemon a ser removido
     * @return JsonResponse
     */

    public function destroy($id): JsonResponse
    {
        $result = $this->pokemonService->deletePokemon($id);

        return $this->successResponse([
            new PokemonResource($result['data'])
        ]);
    }
}
