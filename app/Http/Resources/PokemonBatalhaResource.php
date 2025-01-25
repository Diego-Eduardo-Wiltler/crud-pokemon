<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PokemonBatalhaResource extends JsonResource
{
    private $message;

    public function __construct($resource, $message = null)
    {
        parent::__construct($resource);
        $this->message = $message;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => $this->message,
            'nome' => $this->nome,
            'tipo' => $this->tipo,
            'localizacao' => $this->localizacao,
            'shiny' => $this->shiny,
        ];
    }
}
