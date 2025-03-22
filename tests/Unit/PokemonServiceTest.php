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
    protected $invalidId = 9999;

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

    public static function attackBattle()
    {
        return [
            'Ataque 1' => [1000, 5],
            'Ataque 2' => [5, 1000],
        ];
    }

    // php artisan test --filter=PokemonServiceTest::test_getPokemons_success
    public function test_getPokemons_success()
    {
        $response = $this->pokemonService->getPokemons();
        $listarPokemons = $response['data'];

        $this->assertCount(5, $listarPokemons);
        $this->assertInstanceOf(Collection::class, $listarPokemons);
    }

    // php artisan test --filter=PokemonServiceTest::test_getPokemonById_success
    public function test_getPokemonById_success()
    {
        $pokemon = Pokemon::factory()->create();

        $id = $pokemon->id;

        $response = $this->pokemonService->getById($id);

        $this->assertArrayHasKey('data', $response);
        $this->assertInstanceOf(Pokemon::class, $response['data']);
        $this->assertEquals($id, $response['data']->id);
    }

    // php artisan test --filter=PokemonServiceTest::test_getPokemonById_notFound
    public function test_getPokemonById_notFound()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->pokemonService->getById($this->invalidId);
    }

    // php artisan test --filter=PokemonServiceTest::test_createPokemon_success
    public function test_createPokemon_success()
    {
        $data = $this->getPokemonData();

        $response = $this->pokemonService->createPokemon($data);

        $pokemonCriado = $response['data'];

        $this->assertArrayHasKey('data', $response);
        $this->assertDatabaseHas('pokemons', $data);
        $this->assertInstanceOf(Pokemon::class, $pokemonCriado);
    }

    // php artisan test --filter=PokemonServiceTest::test_createPokemon_missingField
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

        $this->expectException(QueryException::class);

        $this->pokemonService->createPokemon($data);
    }

    #[DataProvider('attackBattle')]
    // php artisan test --filter=PokemonServiceTest::test_battlePokemon_success
    public function test_battlePokemon_success($ataque1, $ataque2)
    {
        $pokemon1 = Pokemon::factory()->create(['ataque' =>$ataque1, 'vida_atual' => 1500]);
        $pokemon2 = Pokemon::factory()->create(['ataque' =>$ataque2, 'vida_atual' => 1000]);

        $response = $this->pokemonService->battlePokemon($pokemon1->id, $pokemon2->id);

        $pokemon1->refresh();
        $pokemon2->refresh();

        $this->assertArrayHasKey('data', $response);
        $this->assertNotEquals($pokemon1->vida, $pokemon1->vida_atual);
        $this->assertNotEquals($pokemon2->vida, $pokemon2->vida_atual);
    }

    // php artisan test --filter=PokemonServiceTest::test_battlePokemon_invalidId
    public function test_battlePokemon_invalidId()
    {
        $this->expectException(ModelNotFoundException::class);

        $pokemon = Pokemon::factory()->create();

        $this->pokemonService->battlePokemon($pokemon->id, 'sdsd');
    }

    // php artisan test --filter=PokemonServiceTest::test_battleRound_success
    #[DataProvider('attackAndDefense')]
    public function test_battleRound_success($ataque, $defesa)
    {
        $pokemon1 = Pokemon::factory()->create(['ataque' => $ataque]);
        $pokemon2 = Pokemon::factory()->create(['defesa' => $defesa]);

        $response = $this->pokemonService->executeRound($pokemon1->id, $pokemon2->id);

        $this->assertArrayHasKey('data', $response);
    }

    // php artisan test --filter=PokemonServiceTest::test_healPokemon_success
    public function test_healPokemon_success()
    {
        $pokemon = Pokemon::factory()->create(['vida' => 50]);
        $pokemon->vida_atual = 20;

        $pokemon->save();

        $response = $this->pokemonService->healPokemon($pokemon->id);

        $pokemon->refresh();

        $this->assertEquals($pokemon->vida, $pokemon->vida_atual);
        $this->assertEquals($pokemon->toArray(), $response['data']->toArray());
    }

    // php artisan test --filter=PokemonServiceTest::test_healPokemon_invalidId
    public function test_healPokemon_invalidId()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->pokemonService->healPokemon($this->invalidId);
    }

    // php artisan test --filter=PokemonServiceTest::test_updatePokemon_success
    public function test_updatePokemon_success()
    {
        $pokemon = Pokemon::factory()->create();

        $dadosAtualizados = $this->getPokemonData();

        $response = $this->pokemonService->updatePokemon($pokemon->id, $dadosAtualizados);

        $this->assertArrayHasKey('data', $response);
        $this->assertDatabaseHas('pokemons', $dadosAtualizados);
        $this->assertInstanceOf(Pokemon::class, $response['data']);
    }

    // php artisan test --filter=PokemonServiceTest::test_updatePokemon_invalidId
    public function test_updatePokemon_invalidId()
    {
        $this->expectException(ModelNotFoundException::class);

        $dadosAtualizados = $this->getPokemonData();

        $this->pokemonService->updatePokemon($this->invalidId, $dadosAtualizados);
    }

    // php artisan test --filter=PokemonServiceTest::test_deletePokemon_success
    public function test_deletePokemon_success()
    {
        $pokemon = $this->pokemons->first();

        $this->pokemonService->deletePokemon($pokemon->id);

        $this->assertDatabaseMissing('pokemons', [
            'id' => $pokemon->id,
        ]);
    }

    // php artisan test --filter=PokemonServiceTest::test_deletePokemon_invalidId
    public function test_deletePokemon_invalidId()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->pokemonService->deletePokemon($this->invalidId);
    }
}
