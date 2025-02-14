<?php

namespace Tests\Unit;

use App\Models\Pokemon;
use App\Models\Treinador;
use App\Services\TreinadorService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TreinadorServiceTest extends TestCase
{
    use RefreshDatabase;

    // php artisan test --filter=TreinadorServiceTest::test_get_treinadores
    public function test_get_treinadores()
    {

        $pokemons = Pokemon::factory()->count(5)->create();

        $treinadores = Treinador::factory()->count(5)->create([
            'pokemon_id' => $pokemons->random()->id,
        ]);

        $service = new TreinadorService();

        $response = $service->getTreinador();

        $listarTreinadores = $response['data'];

        $this->assertCount(5, $listarTreinadores);
        $this->assertInstanceOf(Collection::class, $listarTreinadores);
        $this->assertEquals($treinadores->toArray(), $listarTreinadores->toArray());
    }
}
