<?php

namespace Tests\Unit;

use App\Models\Pokemon;
use App\Models\Treinador;
use App\Services\TreinadorService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use Tests\Traits\SetUpDatabaseTrait;

class TreinadorServiceTest extends TestCase
{
    use RefreshDatabase, SetUpDatabaseTrait;

    protected $treinadorService;
    protected $invalidId = 9999;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
        $this->treinadorService = new TreinadorService();

        //$this->treinadorService = Mockery::mock(TreinadorService::class);
        $this->app->instance(TreinadorService::class, $this->treinadorService);
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

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    // php artisan test --filter=TreinadorServiceTest::test_getTreinadores_success
    public function test_getTreinadores_success()
    {
        $response = $this->treinadorService->getTreinador();

        $listarTreinadores = $response['data'];

        $this->assertCount(5, $listarTreinadores);
        $this->assertInstanceOf(Collection::class, $listarTreinadores);
        $this->assertEquals($this->treinadores->toArray(), $listarTreinadores->toArray());
    }

    // php artisan test --filter=TreinadorServiceTest::test_getTreinadorById_success
    public function test_getTreinadorById_success()
    {
        $treinador = Treinador::factory()->create([
            'pokemon_id' => null
        ]);

        $response = $this->treinadorService->getById($treinador->id);

        $this->assertArrayHasKey('data', $response);
        $this->assertInstanceOf(Treinador::class, $response['data']);
        $this->assertEquals($treinador->id, $response['data']->id);
    }

    // php artisan test --filter=TreinadorServiceTest::test_getTreinadorById_invalidId
    public function test_getTreinadorById_invalidId()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->treinadorService->getById($this->invalidId);
    }

    // php artisan test --filter=TreinadorServiceTest::test_getTreinadoresWithPokemons_success
    public function test_getTreinadoresWithPokemons_success()
    {
        $this->treinadores->load('pokemon');

        $response = $this->treinadorService->getTreinadoresPokemons();

        $listarTreinadores = $response['data'];

        $this->assertCount(5, $listarTreinadores);
        $this->assertArrayHasKey('data', $response);
        $this->assertInstanceOf(Collection::class, $listarTreinadores);
        $this->assertEquals($this->treinadores->toArray(), $listarTreinadores->toArray());
    }

    // php artisan test --filter=TreinadorServiceTest::test_createTreinador_success
    public function test_createTreinador_success()
    {
        $data = $this->getTreinadorData();

        $response = $this->treinadorService->storeTreinador($data);

        $treinadorCriado = $response['data'];

        $this->assertArrayHasKey('data', $response);
        $this->assertDatabaseHas('treinadores', $data);
        $this->assertInstanceOf(Treinador::class, $treinadorCriado);
    }

    // php artisan test --filter=TreinadorServiceTest::test_createTreinador_missingField
    public function test_createTreinador_missingField()
    {
        $this->expectException(QueryException::class);

        $data = [
            'email' => 'treinador@example.com',
            'regiao' => 'Unova',
            'tipo_favorito' => 'Planta',
            'idade' => 18,
            'pokemon_id' => null,
        ];

        $this->treinadorService->storeTreinador($data);
    }

     // php artisan test --filter=TreinadorServiceTest::test_createTreinador_invalidPokemonId
     public function test_createTreinador_invalidPokemonId()
     {
         $this->expectException(QueryException::class);

         $data = [
            'nome' => 'Treinador Teste',
            'email' => 'treinador@example.com',
            'regiao' => 'Unova',
            'tipo_favorito' => 'Planta',
            'idade' => 18,
            'pokemon_id' => 'sssss'
         ];

         $this->treinadorService->storeTreinador($data);
     }

    // php artisan test --filter=TreinadorServiceTest::test_tradeTreinadores_success
    public function test_tradeTreinadores_success()
    {
        $pokemons = Pokemon::factory()->count(2)->create();

        $treinador1 = Treinador::factory()->create(['pokemon_id' => $pokemons[0]->id]);
        $treinador2 = Treinador::factory()->create(['pokemon_id' => $pokemons[1]->id]);

        $response = $this->treinadorService->storeTreinadorTrade($treinador1->id, $treinador2->id);

        $this->assertCount(2, $response['data']);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('trade_message', $response);
    }

    // php artisan test --filter=TreinadorServiceTest::test_tradeTreinadoresLog_success
    public function test_tradeTreinadoresLog_success()
    {
        $pokemons = Pokemon::factory()->count(2)->create();

        $treinador1 = Treinador::factory()->create(['pokemon_id' => $pokemons[0]->id]);
        $treinador2 = Treinador::factory()->create(['pokemon_id' => $pokemons[1]->id]);

        $pokemonOriginalTreinador1 = $treinador1->pokemon_id;
        $pokemonOriginalTreinador2 = $treinador2->pokemon_id;

        $this->treinadorService->storeTreinadorTrade($treinador1->id, $treinador2->id);

        $this->assertDatabaseHas('treinador_trades', [
            'pokemon_id'     => $pokemonOriginalTreinador1,
            'old_trainer_id' => $treinador1->id,
            'new_trainer_id' => $treinador2->id,
        ]);

        $this->assertDatabaseHas('treinador_trades', [
            'pokemon_id'     => $pokemonOriginalTreinador2,
            'old_trainer_id' => $treinador2->id,
            'new_trainer_id' => $treinador1->id,
        ]);
    }

    // php artisan test --filter=TreinadorServiceTest::test_tradeTreinadores_onNull
    public function test_tradeTreinadores_onNull()
    {
        $this->expectException(\Exception::class);

        $treinador1 = Treinador::factory()->create(['pokemon_id' => null]);
        $treinador2 = Treinador::factory()->create(['pokemon_id' => null]);

        $this->treinadorService->storeTreinadorTrade($treinador1->id, $treinador2->id);
    }

    // php artisan test --filter=TreinadorServiceTest::test_tradeTreinadores_invalidId
    public function test_tradeTreinadores_invalidId()
    {
        $this->expectException(\Exception::class);

        $pokemon = Pokemon::factory()->create();

        $treinador = Treinador::factory()->create([
            'pokemon_id' => $pokemon->id
        ]);

        $this->treinadorService->storeTreinadorTrade($treinador->id, $this->invalidId);
    }

    // php artisan test --filter=TreinadorServiceTest::test_updateTreinador_success
    public function test_updateTreinador_success()
    {
        $treinador = $this->treinadores->first();

        $dadosAtualizados = $this->getTreinadorData();

        $response = $this->treinadorService->updateTreinador($treinador->id, $dadosAtualizados);

        $treinadorAtualizado = $response['data'];

        $this->assertArrayHasKey('data', $response);
        $this->assertDatabaseHas('treinadores', $dadosAtualizados);
        $this->assertInstanceOf(Treinador::class, $treinadorAtualizado);
    }

     // php artisan test --filter=TreinadorServiceTest::test_updateTreinador_success
     public function test_updateTreinador_invalidId()
     {
        $this->expectException(ModelNotFoundException::class);

        $dadosAtualizados = $this->getTreinadorData();

        $this->treinadorService->updateTreinador($this->invalidId, $dadosAtualizados);
     }

    // php artisan test --filter=TreinadorServiceTest::test_deleteTreinador_success
    public function test_deleteTreinador_success()
    {
        $treinador = $this->treinadores->first();

        $this->treinadorService->deleteTreinador($treinador->id);

        $this->assertDatabaseMissing('treinadores', [
            'id' => $treinador->id,
        ]);
    }

    // php artisan test --filter=TreinadorServiceTest::test_deleteTreinador_invalidId
    public function test_deleteTreinador_invalidId()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->treinadorService->deleteTreinador($this->invalidId);
    }
}
