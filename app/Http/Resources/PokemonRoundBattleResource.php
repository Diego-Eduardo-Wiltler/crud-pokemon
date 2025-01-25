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
            'messages' => [
                "{$this->resource['pokemonATK']->nome} atacou com {$this->resource['pokemonATK']->ataque} de dano",
                "{$this->resource['pokemonDEF']->nome} {$this->resource['defesaTexto']}",
                "{$this->resource['pokemonATK']->nome} causou {$this->resource['danoCausado']} de dano",
                "{$this->resource['pokemonDEF']->nome} ainda se mantém na luta",
                "{$this->resource['pokemonDEF']->nome} ainda possui {$this->resource['pokemonDEF']->vida} pontos de vida",
            ],
            'data' => [
                'pokemon_atk' => [
                    'id' => $this->resource['pokemonATK']->id,
                    'nome' => $this->resource['pokemonATK']->nome,
                    'ataque' => $this->resource['pokemonATK']->ataque,
                ],
                'pokemon_def' => [
                    'id' => $this->resource['pokemonDEF']->id,
                    'nome' => $this->resource['pokemonDEF']->nome,
                    'defesa' => $this->resource['pokemonDEF']->defesa,
                ],
            ],
        ];
    }
}
