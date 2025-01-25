<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreinadorResource extends JsonResource
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
            'pokemon_id' => $this->pokemon_id,
            "email" => $this->email,
            "regiao" => $this->regiao,
            "tipo_favorito" => $this->tipo_favorito,
            "idade" => $this->idade,
        ];
    }
}
