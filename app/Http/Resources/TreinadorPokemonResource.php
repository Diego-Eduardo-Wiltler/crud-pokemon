<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreinadorPokemonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            "nome" => $this->nome,
            "email" => $this->email,
            "regiao" => $this->regiao,
            "tipo_favorito" => $this->tipo_favorito,
            "idade" => $this->idade,
            "trade" => new TreinadorTradeResource($this->latestTrade),
            'pokemons' => $this->pokemon ? new PokemonResource($this->pokemon) : 'Não possui pokemon'
        ];
    }
}

