<?php

namespace App\Enums;

enum PokemonTypeEnum: string
{
    case FIRE = 'Fogo';
    case WATER = 'Água';
    case GRASS = 'Grama';
    case ELECTRIC = 'Elétrico';
    case GHOST = 'Fantasma';
    case PSYCHIC = 'Psíquico';
    case DRAGON = 'Dragão';

    public function label(): string
    {
        return $this->value;
    }
}
