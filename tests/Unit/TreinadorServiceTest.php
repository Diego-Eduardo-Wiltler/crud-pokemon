<?php

namespace Tests\Unit;

use App\Models\Treinador;
use App\Services\TreinadorService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\SetUpDatabaseTrait;

class TreinadorServiceTest extends TestCase
{
    use RefreshDatabase, SetUpDatabaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    // php artisan test --filter=TreinadorServiceTest::test_get_treinadores
    public function test_get_treinadores()
    {
        $service = new TreinadorService();
        $response = $service->getTreinador();
        $listarTreinadores = $response['data'];

        $this->assertCount(5, $listarTreinadores);
        $this->assertInstanceOf(Collection::class, $listarTreinadores);
        $this->assertEquals($this->treinadores->toArray(), $listarTreinadores->toArray());
    }

    // php artisan test --filter=TreinadorServiceTest::test_get_treinador_by_id
    public function test_get_treinador_by_id()
    {
        $id = $this->treinadores->random()->id;

        $service = new TreinadorService();
        $response = $service->getById($id);

        $this->assertArrayHasKey('data', $response);
        $this->assertInstanceOf(Treinador::class, $response['data']);
        $this->assertEquals($id, $response['data']->id);
    }

    // php artisan test --filter=TreinadorServiceTest::test_get_treinador_pokemon
    public function test_get_treinador_pokemon()
    {
        $this->treinadores->load('pokemon');

        $service = new TreinadorService();
        $response = $service->getTreinadoresPokemons();

        $listarTreinadores = $response['data'];

        $this->assertCount(5, $listarTreinadores);
        $this->assertArrayHasKey('data', $response);
        $this->assertInstanceOf(Collection::class, $listarTreinadores);
        $this->assertEquals($this->treinadores->toArray(), $listarTreinadores->toArray());
    }

    // php artisan test --filter=TreinadorServiceTest::test_store_treinador
    public function test_store_treinador()
    {
        $data = [
            'nome' => 'Treinador Teste',
            'email' => 'treinador@example.com',
            'regiao' => 'Unova',
            'tipo_favorito' => 'Planta',
            'idade' => 18,
            'pokemon_id' => $this->pokemons->random()->id,
        ];

        $service = new TreinadorService();
        $response = $service->storeTreinador($data);

        $treinadorCriado = $response['data'];

        $this->assertArrayHasKey('data', $response);
        $this->assertInstanceOf(Treinador::class, $treinadorCriado);
        $this->assertDatabaseHas('treinadores', [
            'nome' => $data['nome'],
            'email' => $data['email'],
            'regiao' => $data['regiao'],
            'tipo_favorito' => $data['tipo_favorito'],
            'idade' => $data['idade'],
            'pokemon_id' => $data['pokemon_id'],
        ]);
    }

    // php artisan test --filter=TreinadorServiceTest::test_update_treinador
    public function test_update_treinador()
    {
        $treinador = $this->treinadores->first();

        $dadosAtualizados = [
            'nome' => 'Treinador Atualizado',
            'email' => 'atualizado@example.com',
            'regiao' => 'Johto',
            'tipo_favorito' => 'Água',
            'idade' => 30,
            'pokemon_id' => $this->pokemons->random()->id,
        ];

        $service = new TreinadorService();
        $response = $service->updateTreinador($treinador->id, $dadosAtualizados);

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

        $service = new TreinadorService();
        $service->deleteTreinador($treinador->id);

        $this->assertDatabaseMissing('treinadores', [
            'id' => $treinador->id,
        ]);
    }
}
