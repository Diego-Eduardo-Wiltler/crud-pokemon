<?php

namespace Tests\Feature;

use App\Models\Pokemon;
use App\Models\Treinador;
use App\Services\TreinadorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TreinadorFeatureTest extends TestCase
{
    use RefreshDatabase;

    // php artisan test --filter=TreinadorFeatureTest::test_create_treinador_endpoint
    public function test_create_treinador_endpoint()
    {
        $pokemons = Pokemon::factory()->count(5)->create();

        $data = [
            'nome' => 'Treinador Teste',
            'email' => 'treinador@example.com',
            'regiao' => 'Unova',
            'tipo_favorito' => 'Planta',
            'idade' => 18,
            'pokemon_id' => $pokemons->random()->id,
        ];

        $response = $this->postJson('/api/treinadores', $data);
        $responseData = $response->json('data.0');

        $this->assertDatabaseHas('treinadores', [
            'email' => 'treinador@example.com',
        ]);
        $this->assertModelExists(Treinador::find($responseData['id']));
        $this->assertEquals($data, [
            'nome' => $responseData['nome'],
            'email' => $responseData['email'],
            'regiao' => $responseData['regiao'],
            'tipo_favorito' => $responseData['tipo_favorito'],
            'idade' => $responseData['idade'],
            'pokemon_id' => $responseData['pokemon_id'],
        ]);
    }
}
