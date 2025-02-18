<?php

namespace Tests\Traits;

use App\Models\Pokemon;
use App\Models\Treinador;

trait SetUpDatabaseTrait
{
    protected $pokemons;
    protected $treinadores;

    protected function setUpDatabase(): void
    {
        $this->pokemons = Pokemon::factory()->count(5)->create();

        $this->treinadores = Treinador::factory()->count(5)->create([
            'pokemon_id' => fn () => $this->pokemons->random()->id,
        ]);
    }
}
