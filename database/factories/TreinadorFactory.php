<?php

namespace Database\Factories;

use App\Models\Treinador;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Treinador>
 */
class TreinadorFactory extends Factory
{

    protected $model = Treinador::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->name(),
            'pokemon_id' => $this->faker->numberBetween(1, 151),
            'email' => $this->faker->unique()->safeEmail(),
            'regiao' => $this->faker->randomElement(['Kanto', 'Johto', 'Hoenn', 'Sinnoh', 'Unova', 'Kalos', 'Alola', 'Galar']),
            'tipo_favorito' => $this->faker->randomElement(['Fogo', 'Água', 'Grama', 'Elétrico', 'Fantasma', 'Psíquico', 'Dragão']),
            'idade' => $this->faker->numberBetween(10, 50),
        ];
    }
}
