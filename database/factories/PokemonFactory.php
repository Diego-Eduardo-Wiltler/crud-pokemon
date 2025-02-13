<?php

namespace Database\Factories;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PokemonFactory extends Factory
{
    protected $model = Pokemon::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->randomElement(['Pikachu', 'Charmander', 'Bulbasaur', 'Squirtle', 'Eevee']),
            'ataque' => $this->faker->numberBetween(5, 100),
            'defesa' => $this->faker->numberBetween(5, 100),
            'vida' => $this->faker->numberBetween(50, 500),
            'vida_atual' => function (array $attributes) {
                return $attributes['vida'];
            },
            'tipo' => $this->faker->randomElement(['Elétrico', 'Fogo', 'Água', 'Grama', 'Fantasma', 'Psíquico', 'Dragão']),
            'peso' => $this->faker->randomFloat(2, 1, 500) . 'Kg',
            'localizacao' => $this->faker->randomElement([
                'Área selvagem - Rota 4',
                'Floresta de Viridian',
                'Caverna Diglett',
                'Monte Lua',
                'Safari Zone'
            ]),
            'shiny' => $this->faker->boolean(5),
        ];
    }
}
