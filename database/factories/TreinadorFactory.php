<?php

namespace Database\Factories;

use App\Enums\PokemonTypeEnum;
use App\Enums\RegionEnum;
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
            'regiao' => $this->faker->randomElement(RegionEnum::cases())->value,
            'tipo_favorito' => $this->faker->randomElement(PokemonTypeEnum::cases())->value,
            'idade' => $this->faker->numberBetween(10, 50),
        ];
    }
}
