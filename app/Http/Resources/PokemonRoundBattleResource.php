<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PokemonRoundBattleResource extends JsonResource
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
                'nome' => $this->nome,
                'ataque' => $this->ataque ?? null,
                'defesa' => $this->defesa ?? null,
            ];

    }
}
