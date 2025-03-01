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

    /**
     * GET /treinadores
     *
     * Retorna lista de treinadores cadastrados
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: array<TreinadorResource>
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
        $result = $this->treinadorService->getTreinador();

        return $this->successResponse([
            TreinadorResource::collection($result['data'])
        ]);
    }

    /**
     * GET /treinadores/{id}
     *
     * Retorna um unico treinador pelo id
     *
     * @urlParam id int required ID do treinador a ser encontrado. Example: 1
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
     * @param $id ID do treinador a ser encontrado
     *
     */

    public function show($id): JsonResponse
    {
        $result = $this->treinadorService->getBYId($id);

        return $this->successResponse([
            new TreinadorResource($result['data'])
        ]);
    }

    /**
     * GET /treinadores-pokemons
     *
     * Retorna lista de treinadores e seus pokemons cadastrados
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: array<TreinadorPokemonResource>
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     */

    public function indexTreinadorPokemon(): JsonResponse
    {
        $result = $this->treinadorService->getTreinadoresPokemons();

        return $this->successResponse([

            TreinadorPokemonResource::collection($result['data'])
        ]);
    }

    /**
     * POST /treinadores
     *
     * Cadastra um novo treinador
     *
     * @bodyParam nome string required Nome do treinador. Example: Karlos
     * @bodyParam email string required Email do treinador. Example: Karlos@gmail.com
     * @bodyParam regiao string required Região do treinaor. Example: Unova
     * @bodyParam tipo_favorito string required Tipo favorito. Example: Planta
     * @bodyParam idade int required Idade do treinador. Example: 18
     * @bodyParam pokemon_id int Pokemon do treinador. Example: 4
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: TreinadorResource
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param TreinadorStoreFormRequest $request Requisição contendo os dados do treinador.
     * @return JsonResponse
     *
     *
     */

    public function store(TreinadorStoreFormRequest $request): JsonResponse
    {
        $data = $request->validated();
        $result = $this->treinadorService->storeTreinador($data);

        return $this->successResponse([
            new TreinadorResource($result['data'])
        ]);
    }

    public function storeTrade(Request $request): JsonResponse
    {
       $id1 = $request->input('treinador:id1');
       $id2 = $request->input('treinador:id2');

       $result = $this->treinadorService->storeTreinadorTrade($id1, $id2);

       return $this->successResponse([
        // 'trade_message' => $result['trade_message'],
            TreinadorPokemonResource::collection($result['data'])
       ]);
    }

    /**
     * PUT /treinadores/{id}
     *
     * Cadastra um novo treinador
     *
     * @bodyParam nome string  Nome do treinador. Example: Karlos
     * @bodyParam email string  Email do treinador. Example: Karlos@gmail.com
     * @bodyParam regiao string  Região do treinaor. Example: Unova
     * @bodyParam tipo_favorito string  Tipo favorito. Example: Planta
     * @bodyParam idade int  Idade do treinador. Example: 18
     * @bodyParam pokemon_id int Pokemon do treinador. Example: 4
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: TreinadorResource
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param TreinadorUpdateFormRequest $request Requisição contendo os dados do treinador
     * @param int $id ID do treinador a ser atualizado
     * @return JsonResponse
     *
     */

    public function update(TreinadorUpdateFormRequest $request, $id): JsonResponse
    {
        $data = $request->validated();
        $result = $this->treinadorService->updateTreinador($id, $data);

        return $this->successResponse([
            new TreinadorResource($result['data'])
        ]);
    }

    /**
     * DELETE /treinador/{id}
     *
     * Remove um treinador existente
     *
     * @urlParam id int required ID do treinador a ser removido. Example: 1
     *
     * @response 200 array{
     *   success: true,
     *   message: string,
     *   data: TreinadorResource
     * }
     *
     * @response 400 array{
     *   success: false,
     *   message: string
     * }
     *
     * @param int $id ID do treinador a ser removido
     * @return JsonResponse
     */

    public function destroy($id): JsonResponse
    {
        $result = $this->treinadorService->deleteTreinador($id);

        return $this->successResponse([
            new TreinadorResource($result['data'])
        ]);
    }
}
