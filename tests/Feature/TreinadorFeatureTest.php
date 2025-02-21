<?php

namespace Tests\Feature;

use App\Models\Pokemon;
use App\Models\Treinador;
use App\Services\TreinadorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\SetUpDatabaseTrait;

// php artisan test --filter=TreinadorFeatureTest
class TreinadorFeatureTest extends TestCase
{
    use RefreshDatabase, SetUpDatabaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    // php artisan test --filter=TreinadorFeatureTest::test_get_treinadores
    public function test_get_treinadores()
    {
        $response = $this->getJson('/api/treinadores');

        $response->assertJsonCount(5, 'data.0');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                0 => [
                    0 => [
                        'id',
                        'nome',
                        'pokemon_id',
                        'email',
                        'regiao',
                        'tipo_favorito',
                        'idade'
                    ]
                ]
            ]
        ]);
    }

    // php artisan test --filter=TreinadorFeatureTest::test_get_treinador_by_id
    public function test_get_treinador_by_id()
    {
        $treinador = $this->treinadores->first();

        $response = $this->getJson("/api/treinadores/{$treinador->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $treinador->id,
            'nome' => $treinador->nome,
            'email' => $treinador->email,
            'regiao' => $treinador->regiao,
            'tipo_favorito' => $treinador->tipo_favorito,
            'idade' => $treinador->idade
        ]);
    }

    // php artisan test --filter=TreinadorFeatureTest::test_get_treinador_pokemon
    public function test_get_treinador_pokemon()
    {
        $treinador = $this->treinadores->first();

        $response = $this->getJson("/api/treinadores-pokemons");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $treinador->id,
            'nome' => $treinador->nome,
            'email' => $treinador->email,
            'regiao' => $treinador->regiao,
            'tipo_favorito' => $treinador->tipo_favorito,
            'idade' => $treinador->idade
        ]);
    }

    // php artisan test --filter=TreinadorFeatureTest::test_create_treinador
    public function test_create_treinador()
    {
        $data = [
            'nome' => 'Treinador Teste',
            'email' => 'treinador@example.com',
            'regiao' => 'Unova',
            'tipo_favorito' => 'Planta',
            'idade' => 18,
            'pokemon_id' => $this->pokemons->random()->id,
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

    // php artisan test --filter=TreinadorFeatureTest::test_update_treinador
    public function test_update_treinador()
    {
        $treinador = $this->treinadores->first();

        $dadosAtualizados = [
            'nome' => 'Treinador Teste',
            'email' => 'treinador@example.com',
            'regiao' => 'Unova',
            'tipo_favorito' => 'Planta',
            'idade' => 18,
            'pokemon_id' => $this->pokemons->random()->id,
        ];

        $response = $this->putJson("/api/treinadores/{$treinador->id}", $dadosAtualizados);

        $response->assertStatus(200);

        $responseData = $response->json('data.0');

        $this->assertModelExists(Treinador::find($responseData['id']));

        $this->assertEquals($dadosAtualizados['nome'], $responseData['nome']);
        $this->assertEquals($dadosAtualizados['email'], $responseData['email']);
        $this->assertEquals($dadosAtualizados['regiao'], $responseData['regiao']);
        $this->assertEquals($dadosAtualizados['tipo_favorito'], $responseData['tipo_favorito']);
        $this->assertEquals($dadosAtualizados['idade'], $responseData['idade']);
        $this->assertEquals($dadosAtualizados['pokemon_id'], $responseData['pokemon_id']);
    }

    // php artisan test --filter=TreinadorFeatureTest::teste_delete_treinador
    public function teste_delete_treinador()
    {
        $treinador = $this->treinadores->first();

        $response = $this->deleteJson("/api/treinadores/{$treinador->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('treinadores', [
            'id' => $treinador->id,
        ]);
    }
}
