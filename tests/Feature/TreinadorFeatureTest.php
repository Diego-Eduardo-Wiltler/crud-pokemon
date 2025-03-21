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


    protected $invalidId = 9999;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    protected function getTreinadorData(): array
    {
        return [
            'nome' => 'Treinador Teste',
            'email' => 'treinador@example.com',
            'regiao' => 'Unova',
            'tipo_favorito' => 'Planta',
            'idade' => 18,
            'pokemon_id' => $this->pokemons->random()->id,
        ];
    }

    // php artisan test --filter=TreinadorFeatureTest::test_get_treinadores
    public function test_get_treinadores()
    {
        $response = $this->getJson('/api/treinadores');

        $response->assertJsonCount(5, 'data.0');
        $response->assertStatus(200);

    }

    // php artisan test --filter=TreinadorFeatureTest::test_get_by_id_success_on_valid_id_treinador
    public function test_get_by_id_success_on_valid_id_treinador()
    {
        $treinador = Treinador::factory()->create([
            'pokemon_id' => null
        ]);
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

     // php artisan test --filter=TreinadorFeatureTest::test_get_by_id_error_on_invalid_id_treinador
     public function test_get_by_id_error_on_invalid_id_treinador()
     {
         $response = $this->getJson("/api/treinadores/{$this->invalidId}");

         $response->assertStatus(500);
     }

    // php artisan test --filter=TreinadorFeatureTest::test_get_treinador_with_pokemon_on_success
    public function test_get_treinador_with_pokemon_on_success()
    {
        $pokemon = Pokemon::factory()->create();

        $treinador = Treinador::factory()->create([
            'pokemon_id' => $pokemon->id
        ]);

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
    public function test_create_treinador_on_success()
    {
        $data = $this->getTreinadorData();

        $response = $this->postJson('/api/treinadores', $data);
        $response->assertStatus(200);

        $responseData = $response->json('data.0');

        $this->assertDatabaseHas('treinadores', $data);
        $this->assertModelExists(Treinador::find($responseData['id']));
    }

    // php artisan test --filter=TreinadorFeatureTest::test_failing_to_create_on_missing_field_treinador
    public function test_failing_to_create_on_missing_field_treinador()
    {
        $data = [
            'nome' => 'Treinador Teste',
            'email' => 'treinador@example.com',
            'regiao' => 'Unova',
            'tipo_favorito' => 'Planta',

        ];

        $response = $this->postJson('/api/treinadores', $data);
        $response->assertStatus(500);
    }

    // php artisan test --filter=TreinadorFeatureTest::test_failing_to_create_on_invalid_pokemon_id_in_treinador
    public function test_failing_to_create_on_invalid_pokemon_id_in_treinador()
    {
        $data = [
            'nome' => 'Treinador Teste',
            'email' => 'treinador@example.com',
            'regiao' => 'Unova',
            'tipo_favorito' => 'Planta',
            'idade' => 18,
            'pokemon_id' => 'invalidId'
        ];

        $response = $this->postJson('/api/treinadores', $data);
        $response->assertStatus(500);
    }


    // php artisan test --filter=TreinadorFeatureTest::test_trade_treinador
    public function test_trade_on_success_treinador()
    {
        $treinador1 = $this->treinadores->first();
        $treinador2 = $this->treinadores->get(1);

        $data = [
            "treinador:id1" => $treinador1->id,
            "treinador:id2" => $treinador2->id
        ];

        $response = $this->postJson('/api/treinadores-trade', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('treinadores', ['id' => $treinador1->id]);
        $this->assertDatabaseHas('treinadores', ['id' => $treinador2->id]);
    }

     // php artisan test --filter=TreinadorFeatureTest::test_trade_error_on_invalid_id_treinador
     public function test_trade_error_on_invalid_id_treinador()
     {
        $pokemon = Pokemon::factory()->create();

         $treinador1 = Treinador::factory()->create([
            'pokemon_id' => $pokemon->id
         ]);

         $data = [
             "treinador:id1" => $treinador1->id,
             "treinador:id2" => $this->invalidId
         ];

         $response = $this->postJson('/api/treinadores-trade', $data);

         $response->assertStatus(500);
     }

     // php artisan test --filter=TreinadorFeatureTest::test_trade_error_data_treinador
     public function test_trade_error_on_data_treinador()
     {
        $pokemon = Pokemon::factory()->create();

         $treinador = Treinador::factory()->count(2)->create([
            'pokemon_id' => $pokemon->id
         ]);

         $treinador1 = $treinador->first();
         $treinador2 = $treinador->get(1);

         $data = [
             "treindsdador:id1" => $treinador1->id,
             "treinasdddor:id2" => $treinador2->id
         ];

         $response = $this->postJson('/api/treinadores-trade', $data);

         $response->assertStatus(500);
     }

    // php artisan test --filter=TreinadorFeatureTest:test_update_on_success_treinador
    public function test_update_on_success_treinador()
    {
        $treinador = $this->treinadores->first();

        $dadosAtualizados = $this->getTreinadorData();

        $response = $this->putJson("/api/treinadores/{$treinador->id}", $dadosAtualizados);

        $response->assertStatus(200);

        $responseData = $response->json('data.0');

        $this->assertModelExists(Treinador::find($responseData['id']));
        $this->assertDatabaseHas('treinadores', $dadosAtualizados);
    }

    // php artisan test --filter=TreinadorFeatureTest::test_update_treinador_error_on_invalid_id
    public function test_update_treinador_error_on_invalid_id()
    {
        $dadosAtualizados = $this->getTreinadorData();

        $response = $this->putJson("/api/treinadores/{$this->invalidId}", $dadosAtualizados);

        $response->assertStatus(500);
    }

    // php artisan test --filter=TreinadorFeatureTest::test_delete_error_on_valid_id_treinador
    public function test_delete_error_on_valid_id_treinador()
    {
        $treinador = $this->treinadores->first();

        $response = $this->deleteJson("/api/treinadores/{$treinador->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('treinadores', [
            'id' => $treinador->id,
        ]);
    }

    // php artisan test --filter=TreinadorFeatureTest::test_delete_error_on_invalid_id_treinador
    public function test_delete_error_on_invalid_id_treinador()
    {
        $response = $this->deleteJson("/api/treinadores/{$this->invalidId}");

        $response->assertStatus(500);

    }
}
