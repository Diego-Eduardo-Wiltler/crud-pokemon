<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PokemonResource extends JsonResource
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
            'ataque' => $this->ataque,
            'defesa' => $this->defesa,
            'vida' => $this->vida,
            'vida_atual' => $this->vida_atual,
            'tipo' => $this->tipo,
            'peso' => $this->peso,
            'localizacao' => $this->localizacao,
            'shiny' => $this->shiny,
        ];
    }
}
