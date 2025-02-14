<?php

namespace App\Enums;

enum PokemonNameEnum: string{

    CASE PIKACHU = 'Pikachu';
    CASE CHARMANDER = 'Charmander';
    CASE BULBASAUR = 'Bulbasauro';
    CASE SQUIRTLE = 'Squirtle';
    CASE EEVEE = 'Eevee';

    public function label(): string
    {
        return $this->value;
    }

}


