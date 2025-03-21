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

    // php artisan test --filter=TreinadorServiceTest::test_get_treinadores
    public function test_get_treinadores()
    {
        $response = $this->treinadorService->getTreinador();

        $listarTreinadores = $response['data'];

        $this->assertCount(5, $listarTreinadores);
        $this->assertInstanceOf(Collection::class, $listarTreinadores);
        $this->assertEquals($this->treinadores->toArray(), $listarTreinadores->toArray());
    }

    // php artisan test --filter=TreinadorServiceTest::test_get_by_id_success_on_valid_id
    public function test_get_by_id_success_on_valid_id_treinador()
    {
        $treinador = Treinador::factory()->create([
            'pokemon_id' => null
        ]);

        $response = $this->treinadorService->getById($treinador->id);

        $this->assertArrayHasKey('data', $response);
        $this->assertInstanceOf(Treinador::class, $response['data']);
        $this->assertEquals($treinador->id, $response['data']->id);
    }

    // php artisan test --filter=TreinadorServiceTest::test_get_by_id_error_on_invalid_id_treinador
    public function test_get_by_id_error_on_invalid_id_treinador()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->treinadorService->getById($this->invalidId);
    }

    // php artisan test --filter=TreinadorServiceTest::test_get_treinador_pokemon
    public function test_get_treinador_with_pokemon_on_success()
    {
        $this->treinadores->load('pokemon');

        $response = $this->treinadorService->getTreinadoresPokemons();

        $listarTreinadores = $response['data'];

        $this->assertCount(5, $listarTreinadores);
        $this->assertArrayHasKey('data', $response);
        $this->assertInstanceOf(Collection::class, $listarTreinadores);
        $this->assertEquals($this->treinadores->toArray(), $listarTreinadores->toArray());
    }

    // php artisan test --filter=TreinadorServiceTest::test_create_treinador_on_success
    public function test_create_treinador_on_success()
    {
        $data = $this->getTreinadorData();

        $response = $this->treinadorService->storeTreinador($data);

        $treinadorCriado = $response['data'];

        $this->assertArrayHasKey('data', $response);
        $this->assertDatabaseHas('treinadores', $data);
        $this->assertInstanceOf(Treinador::class, $treinadorCriado);
    }

    // php artisan test --filter=TreinadorServiceTest::test_failing_to_create_on_missing_field_treinador
    public function test_failing_to_create_on_missing_field_treinador()
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

     // php artisan test --filter=TreinadorServiceTest::test_failing_to_create_on_invalid_pokemon_id_in_treinador
     public function test_failing_to_create_on_invalid_pokemon_id_in_treinador()
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

    // php artisan test --filter=TreinadorServiceTest::test_trade_on_success_treinadores
    public function test_trade_on_success_treinadores()
    {
        $pokemons = Pokemon::factory()->count(2)->create();

        $treinadores = Treinador::factory()->count(2)->create([
            'pokemon_id' => fn () => $pokemons->random()->id,
        ]);

        $treinador1 = $treinadores->first()->id;
        $treinador2 = $treinadores->get(1)->id;

        $response = $this->treinadorService->storeTreinadorTrade($treinador1, $treinador2);

        $this->assertCount(2, $response['data']);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('trade_message', $response);
    }

    // php artisan test --filter=TreinadorServiceTest::test_trade_error_on_invalid_id_treinador
    public function test_trade_error_on_invalid_id_treinador()
    {
        $this->expectException(\Exception::class);

        $pokemon = Pokemon::factory()->create();

        $treinador = Treinador::factory()->create([
            'pokemon_id' => $pokemon->id
        ]);

        $this->treinadorService->storeTreinadorTrade($treinador->id, $this->invalidId);
    }

    // php artisan test --filter=TreinadorServiceTest::test_update_on_success_treinador
    public function test_update_on_success_treinador()
    {
        $treinador = $this->treinadores->first();

        $dadosAtualizados = $this->getTreinadorData();

        $response = $this->treinadorService->updateTreinador($treinador->id, $dadosAtualizados);

        $treinadorAtualizado = $response['data'];

        $this->assertArrayHasKey('data', $response);
        $this->assertDatabaseHas('treinadores', $dadosAtualizados);
        $this->assertInstanceOf(Treinador::class, $treinadorAtualizado);
    }

     // php artisan test --filter=TreinadorServiceTest::test_update_treinador_error_on_invalid_id
     public function test_update_treinador_error_on_invalid_id()
     {
        $this->expectException(ModelNotFoundException::class);

        $dadosAtualizados = $this->getTreinadorData();

        $this->treinadorService->updateTreinador($this->invalidId, $dadosAtualizados);
     }

    // php artisan test --filter=TreinadorServiceTest::test_delete_success_on_valid_id_treinador
    public function test_delete_success_on_valid_id_treinador()
    {
        $treinador = $this->treinadores->first();

        $this->treinadorService->deleteTreinador($treinador->id);

        $this->assertDatabaseMissing('treinadores', [
            'id' => $treinador->id,
        ]);
    }

    // php artisan test --filter=TreinadorServiceTest::test_delete_error_on_invalid_id
    public function test_delete_error_on_invalid_id()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->treinadorService->deleteTreinador($this->invalidId);
    }
}
