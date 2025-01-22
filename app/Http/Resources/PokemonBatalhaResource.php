<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PokemonBatalhaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => 'O pokemon vencedor é',
            'nome' => $this->nome,
            'tipo' => $this->tipo,
            'localizacao' => $this->localizacao,
            'shiny' => $this->shiny,
        ];
    }
}
