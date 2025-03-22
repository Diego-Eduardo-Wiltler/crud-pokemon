<?php

namespace Tests\Feature;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\SetUpDatabaseTrait;

class PokemonFeatureTest extends TestCase
{

    use RefreshDatabase, SetUpDatabaseTrait;

    protected $invalidId = 9999;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    protected function getPokemonData(): array
    {
        return [
            'nome' => 'Caterpie',
            'ataque' => 5,
            'defesa' => 5,
            'vida' => 45,
            'vida_atual' => 50,
            'tipo' => 'Inseto',
            'peso' => '2.9Kg',
            'localizacao' => 'Floresta Selvagem - Grama Alta',
            'shiny' => 0,
        ];
    }

    // php artisan test --filter=PokemonFeatureTest::test_getPokemons_success
    public function test_getPokemons_success()
    {
        $response = $this->getJson('/api/pokemons');

        $response->assertJsonCount(5, 'data.0');
        $response->assertStatus(200);
    }

    // php artisan test --filter=PokemonFeatureTest::test_getPokemonById_success
    public function test_getPokemonById_success()
    {
        $pokemon = Pokemon::factory()->create();

        $response = $this->getJson("/api/pokemons/{$pokemon->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $pokemon->id,
            'nome' => $pokemon->nome,
            'ataque' => $pokemon->ataque,
            'defesa' => $pokemon->defesa,
            'vida' => $pokemon->vida,
            'vida_atual' => $pokemon->vida_atual,
            'tipo' => $pokemon->tipo,
            'peso' => $pokemon->peso,
            'localizacao' => $pokemon->localizacao,
            'shiny' => (int) $pokemon->shiny,
        ]);
    }

    // php artisan test --filter=PokemonFeatureTest::test_getPokemonById_invalidId
    public function test_getPokemonById_invalidId()
    {
        $response = $this->getJson("api/pokemons/{$this->invalidId}");

        $response->assertStatus(500);
    }

    // php artisan test --filter=PokemonFeatureTest::test_createPokemon_success
    public function test_createPokemon_success()
    {
        $data = $this->getPokemonData();

        $response = $this->postJson('/api/pokemons', $data);
        $response->assertStatus(200);

        $responseData = $response->json('data.0');

        $this->assertModelExists(Pokemon::find($responseData['id']));
        $this->assertDatabaseHas('pokemons', $data);
    }

    // php artisan test --filter=PokemonFeatureTest::test_createPokemon_missingField
    public function test_createPokemon_missingField()
    {
        $data = [
            'ataque' => 5,
            'defesa' => 5,
            'vida' => 45,
            'vida_atual' => 50,
            'tipo' => 'Inseto',
            'peso' => '2.9Kg',
            'localizacao' => 'Floresta Selvagem - Grama Alta',
            'shiny' => 0,
        ];

        $response = $this->postJson('/api/pokemons', $data);
        $response->assertStatus(500);
    }

    // php artisan test --filter=PokemonFeatureTest::test_battlePokemon_success
    public function test_battlePokemon_success()
    {
        $pokemon = Pokemon::factory()->count(2)->create();

        $pokemon1 = $pokemon->first();
        $pokemon2 = $pokemon->get(1);

        $data = [
            "pokemon:id1" => $pokemon1->id,
            "pokemon:id2" => $pokemon2->id
        ];

        $response = $this->postJson('/api/pokemons/battle', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('pokemons', ['id' => $pokemon1->id]);
        $this->assertDatabaseHas('pokemons', ['id' => $pokemon2->id]);
    }

    // php artisan test --filter=PokemonFeatureTest::test_battlePokemon_invalidId
    public function test_battlePokemon_invalidId()
    {
        $pokemon = Pokemon::factory()->create();

        $data = [
            "pokemon:id1" => $pokemon->id,
            "pokemon:id2" => $this->invalidId
        ];

        $response = $this->postJson('/api/pokemons/battle', $data);
        $response->assertStatus(500);

    }

     // php artisan test --filter=PokemonFeatureTest::test_battlePokemon_sameId_error
     public function test_battlePokemon_sameId_error()
     {
         $pokemon = Pokemon::factory()->create();

         $data = [
             "pokemon:id1" => $pokemon->id,
             "pokemon:id2" => $pokemon->id
         ];

         $response = $this->postJson('/api/pokemons/battle', $data);
         $response->assertStatus(500);

     }

     // php artisan test --filter=PokemonFeatureTest::test_battlePokemon_invalidData
     public function test_battlePokemon_invalidData()
     {
        $pokemon = Pokemon::factory()->count(2)->create();

        $pokemon1 = $pokemon->first();
        $pokemon2 = $pokemon->get(1);

        $data = [
            "pokemoswn:id1" => $pokemon1->id,
            "pokemon:234id2" => $pokemon2->id
        ];

        $response = $this->postJson('/api/pokemons/battle', $data);

        $response->assertStatus(500);
     }

    // php artisan test --filter=PokemonFeatureTest::test_battleRound_success
    public function test_battleRound_success()
    {
        $pokemon = Pokemon::factory()->count(2)->create();

        $pokemon1 = $pokemon->first();
        $pokemon2 = $pokemon->get(1);

        $data = [
            "pokemon:id1" => $pokemon1->id,
            "pokemon:id2" => $pokemon2->id
        ];

        $response = $this->postJson('/api/pokemons/round', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('pokemons', ['id' => $pokemon1->id]);
        $this->assertDatabaseHas('pokemons', ['id' => $pokemon2->id]);
    }

     // php artisan test --filter=PokemonFeatureTest::test_battleRound_invalidData
     public function test_battleRound_invalidData()
     {
         $pokemon = Pokemon::factory()->count(2)->create();

         $pokemon1 = $pokemon->first();
         $pokemon2 = $pokemon->get(1);

         $data = [
             "pokemwewon:id1" => $pokemon1->id,
             "pokemeweon:id2" => $pokemon2->id
         ];

         $response = $this->postJson('/api/pokemons/round', $data);

         $response->assertStatus(500);
     }

    // php artisan test --filter=PokemonFeatureTest::test_battleRound_invalidId
    public function test_battleRound_invalidId()
    {
        $data = [
            "pokemon:id1" => $this->invalidId,
            "pokemon:id2" => $this->invalidId
        ];

        $response = $this->postJson('/api/pokemons/round', $data);

        $response->assertStatus(500);
    }

    // php artisan test --filter=PokemonFeatureTest::test_healPokemon_success
    public function test_healPokemon_success()
    {

        $pokemon = Pokemon::factory()->create();
        $pokemon->vida_atual = 20;

        $data = [
            "pokemon:id" => $pokemon->id
        ];

        $pokemon->save();

        $response = $this->postJson('/api/pokemons/healing/', $data);

        $pokemon->refresh();

        $response->assertStatus(200);

        $this->assertEquals($pokemon->vida, $pokemon->vida_atual);
    }

     // php artisan test --filter=PokemonFeatureTest::test_healPokemon_invalidData
     public function test_healPokemon_invalidData()
     {
         $pokemon = Pokemon::factory()->create();

         $data = [
             "pokdsdemon:id" => $pokemon->id
         ];

         $response = $this->postJson('/api/pokemons/healing/', $data);

         $response->assertStatus(500);
     }

     // php artisan test --filter=PokemonFeatureTest::test_healPokemon_invalidId
     public function test_healPokemon_invalidId()
     {
        $data = [
            "pokemon:id" => $this->invalidId
        ];

        $response = $this->postJson('/api/pokemons/healing/', $data);

        $response->assertStatus(500);
     }

    // php artisan test --filter=PokemonFeatureTest::test_updatePokemon_success
    public function test_updatePokemon_success()
    {
        $pokemon = Pokemon::factory()->create();

        $dadosAtualizados = $this->getPokemonData();

        $response = $this->putJson("/api/pokemons/{$pokemon->id}", $dadosAtualizados);

        $response->assertStatus(200);
        $responseData = $response->json('data.0');

        $this->assertModelExists(Pokemon::find($responseData['id']));
        $this->assertDatabaseHas('pokemons', $dadosAtualizados);
    }

    // php artisan test --filter=PokemonFeatureTest::test_updatePokemon_invalidId
    public function test_updatePokemon_invalidId()
    {
        $dadosAtualizados = $this->getPokemonData();

        $response = $this->putJson("/api/pokemons/{$this->invalidId}", $dadosAtualizados);
        $response->assertStatus(500);
    }

    // php artisan test --filter=PokemonFeatureTest::test_deletePokemon_success
    public function test_deletePokemon_success()
    {
        $pokemon = Pokemon::factory()->create();

        $response = $this->deleteJson("/api/pokemons/{$pokemon->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('pokemons', [
            'id' => $pokemon->id,
        ]);
    }

    // php artisan test --filter=PokemonFeatureTest::test_deletePokemon_invalidId
    public function test_deletePokemon_invalidId()
    {
        $response = $this->deleteJson("/api/pokemons/{$this->invalidId}");

        $response->assertStatus(500);
    }
}
