<?php

namespace Tests\Unit;

use App\Models\Pokemon;
use App\Services\PokemonService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Traits\SetUpDatabaseTrait;

// php artisan test --filter=PokemonServiceTest
class PokemonServiceTest extends TestCase
{
    use RefreshDatabase, SetUpDatabaseTrait;

    protected $pokemonService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
        $this->pokemonService = new PokemonService();
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

    public static function attackAndDefense()
    {
        return [
            'Sem defesa' => [200, 5],
            'Defesa parcial' => [15, 5],
            'Defesa moderada'  => [15, 10],
            'Defesa total (metade do dano)' => [10, 10],
            'Defesa extrema'  => [10, 400],
        ];
    }

    // php artisan test --filter=PokemonServiceTest::test_get_pokemons
    public function test_get_pokemons()
    {
        $response = $this->pokemonService->getPokemons();
        $listarPokemons = $response['data'];

        $this->assertCount(5, $listarPokemons);
        $this->assertInstanceOf(Collection::class, $listarPokemons);
    }

    // php artisan test --filter=PokemonServiceTest::test_get_by_id_success_on_valid_id
    public function test_get_by_id_success_on_valid_id()
    {
        $pokemon = Pokemon::factory()->create();

        $id = $pokemon->id;

        $response = $this->pokemonService->getById($id);

        $this->assertArrayHasKey('data', $response);
        $this->assertInstanceOf(Pokemon::class, $response['data']);
        $this->assertEquals($id, $response['data']->id);
    }

    // php artisan test --filter=PokemonServiceTest::test_get_by_id_error_on_invalid_id
    public function test_get_by_id_error_on_invalid_id()
    {
        $this->expectException(ModelNotFoundException::class);

        $invalidId = 99999;

        $this->pokemonService->getById($invalidId);
    }

    // php artisan test --filter=PokemonServiceTest::test_create_pokemon_on_success
    public function test_create_pokemon_on_success()
    {
        $data = $this->getPokemonData();

        $response = $this->pokemonService->createPokemon($data);

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


    // php artisan test --filter=PokemonServiceTest::test_failing_to_create_on_missing_field_pokemon
    public function test_failing_to_create_on_missing_field_pokemon()
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

        $this->expectException(QueryException::class);

        $this->pokemonService->createPokemon($data);
    }

    // php artisan test --filter=PokemonServiceTest::test_battle_success_pokemon
    public function test_battle_success_pokemon()
    {
        $pokemon = Pokemon::factory()->count(2)->create();

        $pokemon1 = $pokemon->first();
        $pokemon2 = $pokemon->get(1);

        $response = $this->pokemonService->battlePokemon($pokemon1->id, $pokemon2->id);

        $pokemon1->refresh();
        $pokemon2->refresh();

        $this->assertArrayHasKey('data', $response);
        $this->assertNotEquals($pokemon1->vida, $pokemon1->vida_atual);
        $this->assertNotEquals($pokemon2->vida, $pokemon2->vida_atual);
    }

    // php artisan test --filter=PokemonServiceTest::test_battle_pokemon_on_invalid_id
    public function test_battle_pokemon_on_invalid_id()
    {
        $this->expectException(ModelNotFoundException::class);

        $pokemon = Pokemon::factory()->create();

        $this->pokemonService->battlePokemon($pokemon->id, 'sdsd');
    }

    // php artisan test --filter=PokemonServiceTest::test_multiples_success_round_battle_pokemon
    #[DataProvider('attackAndDefense')]
    public function test_multiples_success_round_battle_pokemon($ataque, $defesa)
    {
        $pokemon1 = Pokemon::factory()->create(['ataque' => $ataque]);
        $pokemon2 = Pokemon::factory()->create(['defesa' => $defesa]);

        $response = $this->pokemonService->executeRound($pokemon1->id, $pokemon2->id);

        $this->assertArrayHasKey('data', $response);

    }


    // php artisan test --filter=PokemonServiceTest::test_heal_pokemon
    public function test_heal_on_success_id_pokemon()
    {
        $pokemon = Pokemon::factory()->create(['vida' => 50]);
        $pokemon->vida_atual = 20;

        $pokemon->save();

        $response = $this->pokemonService->healPokemon($pokemon->id);

        $pokemon->refresh();

        $this->assertEquals($pokemon->vida, $pokemon->vida_atual);
        $this->assertEquals($pokemon->toArray(), $response['data']->toArray());
        $this->assertArrayHasKey('data', $response);
    }

    // php artisan test --filter=PokemonServiceTest::test_heal_pokemon_on_invalid_id
    public function test_heal_pokemon_on_invalid_id()
    {
        $this->expectException(ModelNotFoundException::class);

        $invalidId = 99999;

        $this->pokemonService->healPokemon($invalidId);
    }

    // php artisan test --filter=PokemonServiceTest::test_update_pokemon
    public function test_update_on_success_pokemon()
    {
        $pokemon = Pokemon::factory()->create();

        $dadosAtualizados = $this->getPokemonData();

        $response = $this->pokemonService->updatePokemon($pokemon->id, $dadosAtualizados);

        $pokemonAtualizado = $response['data'];

        $this->assertArrayHasKey('data', $response);
        $this->assertInstanceOf(Pokemon::class, $pokemonAtualizado);
    }

    // php artisan test --filter=PokemonServiceTest::test_update_pokemon_error_on_invalid_id
    public function test_update_pokemon_error_on_invalid_id()
    {
        $this->expectException(ModelNotFoundException::class);

        $dadosAtualizados = $this->getPokemonData();

        $invalidId = 99999;

        $this->pokemonService->updatePokemon($invalidId, $dadosAtualizados);
    }

    // php artisan test --filter=PokemonServiceTest::test_delete_pokemon
    public function test_delete_success_on_valid_id_pokemon()
    {
        $pokemon = $this->pokemons->first();

        $this->pokemonService->deletePokemon($pokemon->id);

        $this->assertDatabaseMissing('pokemons', [
            'id' => $pokemon->id,
        ]);
    }

    // php artisan test --filter=PokemonServiceTest::test_delete_error_on_invalid_id_pokemon
    public function test_delete_error_on_invalid_id_pokemon()
    {
        $this->expectException(ModelNotFoundException::class);

        $invalidId = 99999;

        $this->pokemonService->deletePokemon($invalidId);
    }
}
