<?php

namespace Tests\Unit;

use App\Models\Treinador;
use App\Services\TreinadorService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use Tests\Traits\SetUpDatabaseTrait;

class TreinadorServiceTest extends TestCase
{
    use RefreshDatabase, SetUpDatabaseTrait;

    protected $treinadorService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
        $this->treinadorService = new TreinadorService();

        $this->treinadorService = Mockery::mock(TreinadorService::class);
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

    // php artisan test --filter=TreinadorServiceTest::test_get_treinador_by_id
    public function test_get_treinador_by_id()
    {
        $id = $this->treinadores->random()->id;

        $response = $this->treinadorService->getById($id);

        $this->assertArrayHasKey('data', $response);
        $this->assertInstanceOf(Treinador::class, $response['data']);
        $this->assertEquals($id, $response['data']->id);
    }

    // php artisan test --filter=TreinadorServiceTest::test_get_treinador_pokemon
    public function test_get_treinador_pokemon()
    {
        $this->treinadores->load('pokemon');

        $response = $this->treinadorService->getTreinadoresPokemons();

        $listarTreinadores = $response['data'];

        $this->assertCount(5, $listarTreinadores);
        $this->assertArrayHasKey('data', $response);
        $this->assertInstanceOf(Collection::class, $listarTreinadores);
        $this->assertEquals($this->treinadores->toArray(), $listarTreinadores->toArray());
    }

    // php artisan test --filter=TreinadorServiceTest::test_create_treinador
    public function test_create_treinador()
    {
        $data = $this->getTreinadorData();

        $this->treinadorService
            ->shouldReceive('storeTreinador')
            ->with($data)
            ->andReturn([
                'data' => new Treinador($data)
            ]);

        $response = $this->treinadorService->storeTreinador($data);

        $treinadorCriado = $response['data'];

        $this->assertArrayHasKey('data', $response);

        $this->assertInstanceOf(Treinador::class, $treinadorCriado);

        // Verifica se os dados foram persistidos no banco de dados
        // $this->assertDatabaseHas('treinadores', [
        //     'nome' => $data['nome'],
        //     'email' => $data['email'],
        //     'regiao' => $data['regiao'],
        //     'tipo_favorito' => $data['tipo_favorito'],
        //     'idade' => $data['idade'],
        //     'pokemon_id' => $data['pokemon_id'],
        // ]);
    }

    // php artisan test --filter=TreinadorServiceTest::test_update_treinador
    public function test_update_treinador()
    {
        $treinador = $this->treinadores->first();

        $dadosAtualizados = $this->getTreinadorData();

        $response = $this->treinadorService->updateTreinador($treinador->id, $dadosAtualizados);

        $treinadorAtualizado = $response['data'];

        $this->assertArrayHasKey('data', $response);
        $this->assertInstanceOf(Treinador::class, $treinadorAtualizado);
        $this->assertEquals($dadosAtualizados['nome'], $treinadorAtualizado->nome);
        $this->assertEquals($dadosAtualizados['email'], $treinadorAtualizado->email);
        $this->assertEquals($dadosAtualizados['regiao'], $treinadorAtualizado->regiao);
        $this->assertEquals($dadosAtualizados['tipo_favorito'], $treinadorAtualizado->tipo_favorito);
        $this->assertEquals($dadosAtualizados['idade'], $treinadorAtualizado->idade);
        $this->assertEquals($dadosAtualizados['pokemon_id'], $treinadorAtualizado->pokemon_id);
        $this->assertDatabaseHas('treinadores', $dadosAtualizados);
    }

    // php artisan test --filter=TreinadorServiceTest::test_delete_treinador
    public function test_delete_treinador()
    {
        $treinador = $this->treinadores->first();

        $this->treinadorService->deleteTreinador($treinador->id);

        $this->assertDatabaseMissing('treinadores', [
            'id' => $treinador->id,
        ]);
    }
}
