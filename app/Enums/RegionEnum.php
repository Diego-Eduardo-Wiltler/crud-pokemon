<?php

namespace App\Enums;

enum RegionEnum: string
{
    case KANTO = 'Kanto';
    case JOHTO = 'Johto';
    case HOENN = 'Hoenn';
    case SINNOH = 'Sinnoh';
    case UNOVA = 'Unova';
    case KALOS = 'Kalos';
    case ALOLA = 'Alola';
    case GALAR = 'Galar';

    public function label(): string
    {
        return $this->value;
    }
}
