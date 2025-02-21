<?php

namespace Tests\Feature;

use App\Models\Pokemon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\SetUpDatabaseTrait;

class PokemonFeatureTest extends TestCase
{

    use RefreshDatabase, SetUpDatabaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    // php artisan test --filter=PokemonFeatureTest::test_get_pokemons
    public function test_get_pokemons()
    {
        $response = $this->getJson('/api/pokemons');

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
                        'ataque',
                        'defesa',
                        'vida',
                        'vida_atual',
                        'tipo',
                        'peso',
                        'localizacao',
                        'shiny',
                    ]
                ]
            ]
        ]);
    }

    // php artisan test --filter=PokemonFeatureTest::test_get_pokemon_by_id
    public function test_get_pokemon_by_id()
    {
        $pokemon = $this->pokemons->first();

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

    // php artisan test --filter=PokemonFeatureTest::test_create_pokemon
    public function test_create_pokemon()
    {
        $data = [
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

        $response = $this->postJson('/api/pokemons', $data);
        $responseData = $response->json('data.0');

        $this->assertModelExists(Pokemon::find($responseData['id']));
        $this->assertEquals($data, [
            'nome' => $responseData['nome'],
            'ataque' => $responseData['ataque'],
            'defesa' => $responseData['defesa'],
            'vida' => $responseData['vida'],
            'vida_atual' => $responseData['vida_atual'],
            'tipo' => $responseData['tipo'],
            'peso' => $responseData['peso'],
            'localizacao' => $responseData['localizacao'],
            'shiny' => $responseData['shiny'],
        ]);
    }

     // php artisan test --filter=PokemonFeatureTest::test_battle_pokemon
    public function test_battle_pokemon()
    {
        $pokemon1 = $this->pokemons->first();
        $pokemon2 = $this->pokemons->get(1);

        $data = [
            "pokemon:id1" => $pokemon1->id,
            "pokemon:id2" => $pokemon2->id
        ];

        $response = $this->postJson('/api/pokemons/battle', $data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'win_message',
                'pokemon' => [
                    'nome',
                    'tipo',
                    'localizacao',
                    'shiny'
                ]
            ]
        ]);

        $this->assertDatabaseHas('pokemons', ['id' => $pokemon1->id]);
        $this->assertDatabaseHas('pokemons', ['id' => $pokemon2->id]);

    }

    // php artisan test --filter=PokemonFeatureTest::test_execute_round
    public function test_execute_round()
    {
        $pokemon1 = $this->pokemons->first();
        $pokemon2 = $this->pokemons->get(2);

        $data = [
            "pokemon:id1" => $pokemon1->id,
            "pokemon:id2"=> $pokemon2->id
        ];

        $response = $this->postJson('/api/pokemons/round', $data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'battle_message' => [],
                'pokemons' => [
                    '*' => [
                        'id',
                        'nome',
                        'ataque',
                        'defesa'
                    ]
                ]
            ]
        ]);

        $this->assertDatabaseHas('pokemons', ['id' => $pokemon1->id]);
        $this->assertDatabaseHas('pokemons', ['id' => $pokemon2->id]);

    }

    // php artisan test --filter=PokemonFeatureTest::test_heal_pokemon
    public function test_heal_pokemon()
    {

        $pokemon = $this->pokemons->get(2);
        $pokemon->vida_atual = 20;

        $data = [
            "pokemon:id" => $pokemon->id
        ];

        $pokemon->save();

        $response = $this->postJson('/api/pokemons/healing/', $data);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'life_recover',
                'pokemon' => [
                    'id',
                    'nome',
                    'ataque',
                    'defesa',
                    'vida',
                    'vida_atual',
                    'tipo',
                    'peso',
                    'localizacao',
                    'shiny',
                ],
            ],
        ]);
    }

    // php artisan test --filter=PokemonFeatureTest::test_update_pokemon
    public function test_update_pokemon()
    {
        $pokemon = $this->pokemons->random();

        $dadosAtualizados = [
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

        $response = $this->putJson("/api/pokemons/{$pokemon->id}", $dadosAtualizados);

        $response->assertStatus(200);

        $responseData = $response->json('data.0');

        $this->assertModelExists(Pokemon::find($responseData['id']));
        $this->assertEquals($dadosAtualizados['nome'], $responseData['nome']);
        $this->assertEquals($dadosAtualizados['ataque'], $responseData['ataque']);
        $this->assertEquals($dadosAtualizados['defesa'], $responseData['defesa']);
        $this->assertEquals($dadosAtualizados['vida'], $responseData['vida']);
        $this->assertEquals($dadosAtualizados['vida_atual'], $responseData['vida_atual']);
        $this->assertEquals($dadosAtualizados['tipo'], $responseData['tipo']);
        $this->assertEquals($dadosAtualizados['peso'], $responseData['peso']);
        $this->assertEquals($dadosAtualizados['localizacao'], $responseData['localizacao']);
        $this->assertEquals($dadosAtualizados['shiny'], $responseData['shiny']);

    }

     // php artisan test --filter=PokemonFeatureTest::test_delete_pokemon
     public function test_delete_pokemon()
     {
         $pokemon = $this->pokemons->random();

         $response = $this->deleteJson("/api/pokemons/{$pokemon->id}");

         $response->assertStatus(200);

         $this->assertDatabaseMissing('pokemons', [
             'id' => $pokemon->id,
         ]);
     }
}
