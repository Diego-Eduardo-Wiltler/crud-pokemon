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

    // php artisan test --filter=TreinadorFeatureTest::test_getTreinadores_success
    public function test_getTreinadores_success()
    {
        $response = $this->getJson('/api/treinadores');

        $response->assertJsonCount(5, 'data.0');
        $response->assertStatus(200);

    }

    // php artisan test --filter=TreinadorFeatureTest::test_getTreinadorById_success
    public function test_getTreinadorById_success()
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

     // php artisan test --filter=TreinadorFeatureTest::test_getTreinadorById_invalidId
     public function test_getTreinadorById_invalidId()
     {
         $response = $this->getJson("/api/treinadores/{$this->invalidId}");

         $response->assertStatus(500);
     }

    // php artisan test --filter=TreinadorFeatureTest::test_getTreinadoresWithPokemons_success
    public function test_getTreinadoresWithPokemons_success()
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

    // php artisan test --filter=TreinadorFeatureTest::test_createTreinador_success
    public function test_createTreinador_success()
    {
        $data = $this->getTreinadorData();

        $response = $this->postJson('/api/treinadores', $data);
        $response->assertStatus(200);

        $responseData = $response->json('data.0');

        $this->assertDatabaseHas('treinadores', $data);
        $this->assertModelExists(Treinador::find($responseData['id']));
    }

    // php artisan test --filter=TreinadorFeatureTest::test_createTreinador_missingField
    public function test_createTreinador_missingField()
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

    // php artisan test --filter=TreinadorFeatureTest::test_createTreinador_invalidPokemonId
    public function test_createTreinador_invalidPokemonId()
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


    // php artisan test --filter=TreinadorFeatureTest::test_tradeTreinadores_success
    public function test_tradeTreinadores_success()
    {
        $pokemons = Pokemon::factory()->count(2)->create();

        $treinador1 = Treinador::factory()->create(["pokemon_id" => $pokemons[0]->id]);
        $treinador2 = Treinador::factory()->create(["pokemon_id" => $pokemons[1]->id]);

        $data = [
            "treinador:id1" => $treinador1->id,
            "treinador:id2" => $treinador2->id
        ];

        $response = $this->postJson('/api/treinadores-trade', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('treinadores', ['id' => $treinador1->id]);
        $this->assertDatabaseHas('treinadores', ['id' => $treinador2->id]);
    }

    // php artisan test --filter=TreinadorFeatureTest::test_tradeTreinadoresLog_success
    public function test_tradeTreinadoresLog_success()
    {

        $pokemons = Pokemon::factory()->count(2)->create();

        $treinador1 = Treinador::factory()->create(['pokemon_id' => $pokemons[0]->id]);
        $treinador2 = Treinador::factory()->create(['pokemon_id' => $pokemons[1]->id]);

        $data = [
            "treinador:id1" => $treinador1->id,
            "treinador:id2" => $treinador2->id
        ];

        $response = $this->postJson('/api/treinadores-trade', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('treinador_trades', [
            'pokemon_id'     => $pokemons[0]->id,
            'old_trainer_id' => $treinador1->id,
            'new_trainer_id' => $treinador2->id,
        ]);

        $this->assertDatabaseHas('treinador_trades', [
            'pokemon_id'     => $pokemons[1]->id,
            'old_trainer_id' => $treinador2->id,
            'new_trainer_id' => $treinador1->id,
        ]);
    }

     // php artisan test --filter=TreinadorFeatureTest::test_tradeTreinadores_onNull
     public function test_tradeTreinadores_onNull()
     {
         $treinador1 = Treinador::factory()->create(['pokemon_id' => null]);
         $treinador2 = Treinador::factory()->create(['pokemon_id' => null]);

         $data = [
            "treinador:id1" => $treinador1->id,
            "treinador:id2" => $treinador2->id
        ];

         $response = $this->postJson('/api/treinadores-trade', $data);

         $response->assertStatus(500);
     }

     // php artisan test --filter=TreinadorFeatureTest::test_tradeTreinadores_invalidId
     public function test_tradeTreinadores_invalidId()
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

     // php artisan test --filter=TreinadorFeatureTest::test_tradeTreinadores_invalidData
     public function test_tradeTreinadores_invalidData()
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

    // php artisan test --filter=TreinadorFeatureTest:test_updateTreinador_success
    public function test_updateTreinador_success()
    {
        $treinador = $this->treinadores->first();

        $dadosAtualizados = $this->getTreinadorData();

        $response = $this->putJson("/api/treinadores/{$treinador->id}", $dadosAtualizados);

        $response->assertStatus(200);

        $responseData = $response->json('data.0');

        $this->assertModelExists(Treinador::find($responseData['id']));
        $this->assertDatabaseHas('treinadores', $dadosAtualizados);
    }

    // php artisan test --filter=TreinadorFeatureTest::test_updateTreinador_invalidId
    public function test_updateTreinador_invalidId()
    {
        $dadosAtualizados = $this->getTreinadorData();

        $response = $this->putJson("/api/treinadores/{$this->invalidId}", $dadosAtualizados);

        $response->assertStatus(500);
    }

    // php artisan test --filter=TreinadorFeatureTest::test_deleteTreinador_success
    public function test_deleteTreinador_success()
    {
        $treinador = $this->treinadores->first();

        $response = $this->deleteJson("/api/treinadores/{$treinador->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('treinadores', [
            'id' => $treinador->id,
        ]);
    }

    // php artisan test --filter=TreinadorFeatureTest::test_deleteTreinador_invalidId
    public function test_deleteTreinador_invalidId()
    {
        $response = $this->deleteJson("/api/treinadores/{$this->invalidId}");

        $response->assertStatus(500);

    }
}
