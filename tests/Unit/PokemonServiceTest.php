<?php

namespace Tests\Unit;

use App\Models\Pokemon;
use App\Services\PokemonService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\SetUpDatabaseTrait;

// php artisan test --filter=PokemonServiceTest
class PokemonServiceTest extends TestCase
{
    use RefreshDatabase, SetUpDatabaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    // php artisan test --filter=PokemonServiceTest::test_get_pokemons
    public function test_get_pokemons()
    {
        $service = new PokemonService();
        $response = $service->getPokemons();
        $listarPokemons = $response['data'];

        $this->assertCount(5, $listarPokemons);
        $this->assertInstanceOf(Collection::class, $listarPokemons);
        $this->assertEquals($this->pokemons->toArray(), $listarPokemons->toArray());
    }

    // php artisan test --filter=PokemonServiceTest::test_get_pokemon_by_id
    public function test_get_pokemon_by_id()
    {
        $id = $this->pokemons->random()->id;

        $service = new PokemonService();
        $response = $service->getById($id);

        $this->assertArrayHasKey('data', $response);
        $this->assertInstanceOf(Pokemon::class, $response['data']);
        $this->assertEquals($id, $response['data']->id);
    }

    // php artisan test --filter=PokemonServiceTest::test_create_pokemon
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

        $service = new PokemonService;
        $response = $service->createPokemon($data);

        $pokemonCriado = $response['data'];

        $this->assertArrayHasKey('data', $response);
        $this->assertInstanceOf(Pokemon::class, $pokemonCriado);
        $this->assertDatabaseHas('pokemons', [
            'nome' => $data['nome'],
            'ataque' => $data['ataque'],
            'defesa' => $data['defesa'],
            'vida' => $data['vida'],
            'vida_atual' => $data['vida_atual'],
            'tipo' => $data['tipo'],
            'peso' => $data['peso'],
            'localizacao' => $data['localizacao'],
            'shiny' => $data['shiny'],
        ]);
    }

    // php artisan test --filter=PokemonServiceTest::test_battle_pokemon
    public function test_battle_pokemon()
    {
        $pokemon1 = $this->pokemons->first();
        $pokemon2 = $this->pokemons->get(1);

        $service = New PokemonService;
        $response = $service->battlePokemon($pokemon1->id, $pokemon2->id);

        $pokemon1->refresh();
        $pokemon2->refresh();

        $this->assertArrayHasKey('data', $response);
        $this->assertNotEquals($pokemon1->vida, $pokemon1->vida_atual);
        $this->assertNotEquals($pokemon2->vida, $pokemon2->vida_atual);

    }

    // php artisan test --filter=PokemonServiceTest::test_execute_round
    public function test_execute_round()
    {
        $pokemon1 = $this->pokemons->first();
        $pokemon2 = $this->pokemons->get(1);

        $service = New PokemonService;
        $response = $service->executeRound($pokemon1->id , $pokemon2->id);

        $pokemon2->refresh();

        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('battle_message', $response);
        $this->assertArrayHasKey('damage_dealt', $response);
        $this->assertArrayHasKey('defesa_texto', $response);
        $this->assertNotEquals($pokemon2->vida, $pokemon2->vida_atual);
        $this->assertLessThanOrEqual($pokemon2->vida, $pokemon2->vida_atual + $response['damage_dealt']);
        $this->assertNotEquals($pokemon2->vida, $pokemon2->vida_atual);

    }

    // php artisan test --filter=PokemonServiceTest::test_heal_pokemon
    public function test_heal_pokemon()
    {
        $pokemon = $this->pokemons->first();
        $pokemon->vida_atual = 20;

        $pokemon->save();

        $service = New PokemonService();
        $response = $service->healPokemon($pokemon->id);

        $pokemon->refresh();

        $this->assertEquals($pokemon->vida, $pokemon->vida_atual);
        $this->assertEquals($pokemon->toArray(), $response['data']->toArray());
        $this->assertArrayHasKey('data', $response);
    }

    // php artisan test --filter=PokemonServiceTest::test_update_pokemon
    public function test_update_pokemon()
    {
        $pokemon = $this->pokemons->first();
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

        $service = New PokemonService;
        $response = $service->updatePokemon($pokemon->id, $dadosAtualizados);

        $pokemonAtualizado = $response['data'];

        $this->assertArrayHasKey('data', $response);
        $this->assertInstanceOf(Pokemon::class, $pokemonAtualizado);
        $this->assertEquals($dadosAtualizados['nome'], $pokemonAtualizado->nome);
        $this->assertEquals($dadosAtualizados['ataque'], $pokemonAtualizado->ataque);
        $this->assertEquals($dadosAtualizados['defesa'], $pokemonAtualizado->defesa);
        $this->assertEquals($dadosAtualizados['vida'], $pokemonAtualizado->vida);
        $this->assertEquals($dadosAtualizados['vida_atual'], $pokemonAtualizado->vida_atual);
        $this->assertEquals($dadosAtualizados['tipo'], $pokemonAtualizado->tipo);
        $this->assertEquals($dadosAtualizados['peso'], $pokemonAtualizado->peso);
        $this->assertEquals($dadosAtualizados['localizacao'], $pokemonAtualizado->localizacao);
        $this->assertEquals($dadosAtualizados['shiny'], $pokemonAtualizado->shiny);
    }

    // php artisan test --filter=PokemonServiceTest::test_delete_pokemon
    public function test_delete_pokemon()
    {
        $pokemon = $this->pokemons->first();

        $service = New PokemonService;
        $response = $service->deletePokemon($pokemon->id);

        $teste = $response;

        $this->assertDatabaseMissing('pokemons', [
            'id' => $pokemon->id,
        ]);
    }
}
