<?php

namespace App\Services;

use App\Models\Treinador;
use App\Models\TreinadorTrade;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class TreinadorService
{
    /**
     * Obtém uma lista de treinadores ordenada por ID
     *
     * @return array{message: string, data: \Illuminate\Database\Eloquent\Collection}
     */
    public function getTreinador(): array
    {
        $treinadores = Treinador::orderBy('id', 'ASC')->get();

        return [
            'message' => 'Lista de treinadores encontrados',
            'data' => $treinadores,
        ];
    }

    /**
     * Obtém uma lista de treinadores com seus respectivos pokémons
     *
     * @return array{message: string, data: \Illuminate\Database\Eloquent\Collection}
     */
    public function getTreinadoresPokemons(): array
    {
        $treinadores = Treinador::with('pokemon')->get();

        return [
            'message' => 'Treinadores e pokémons encontrados',
            'data' => $treinadores,
        ];
    }

    /**
     * Busca um treinador pelo ID
     *
     * @param int $id ID do treinador
     * @return array{message: string, data: \App\Models\Treinador|null}
     */
    public function getById(int $id): array
    {
        $treinador = Treinador::findOrFail($id);

        return [
            'message' => 'Treinador encontrado',
            'data' => $treinador,
        ];
    }

    /**
     * Cadastra um novo treinador no banco de dados
     *
     * @param array $data Dados do treinador a ser cadastrado
     * @return array{message: string, data: \App\Models\Treinador|null}
     */
    public function storeTreinador(array $data): array
    {
        DB::beginTransaction();

        $treinador = Treinador::create($data);

        DB::commit();

        return [
            'message' => 'Treinador cadastrado',
            'data' => $treinador,
        ];
    }

    public function storeTreinadorTrade($id1, $id2)
    {
        try {

            DB::beginTransaction();

            $treinador1 = Treinador::findOrFail($id1);
            $treinador2 = Treinador::findOrFail($id2);

            $pokemon1 = $treinador1->pokemon_id;
            $pokemon2 = $treinador2->pokemon_id;

            [$treinador1->pokemon_id, $treinador2->pokemon_id] = [$pokemon2, $pokemon1];

            $treinador1->save();
            $treinador2->save();

            DB::commit();

            $treinadores = [
                $treinador1,
                $treinador2,
            ];

            TreinadorTrade::create([
                'pokemon_id'     => $pokemon1,
                'old_trainer_id' => $treinador1->id,
                'new_trainer_id' => $treinador2->id,
                'traded_at'      => now(),
            ]);
            TreinadorTrade::create([
                'pokemon_id'     => $pokemon2,
                'old_trainer_id' => $treinador2->id,
                'new_trainer_id' => $treinador1->id,
                'traded_at'      => now(),
            ]);

            $trade_message = [
                'Iniciando troca...',
                'O treinador ' . $treinador1->nome . ' ofereceu ' . ($treinador2->pokemon->nome ?? 'Nada'),
                'Em troca, o treinador ' . $treinador2->nome . ' ofereceu ' . ($treinador1->pokemon->nome ?? 'Nada'),
                'A troca foi realizada com sucesso...',
            ];

            return [
                'trade_message' => $trade_message,
                'data' => $treinadores
            ];
        } catch (Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Atualiza os dados de um treinador existente
     *
     * @param array $data Dados atualizados do treinador
     * @param int $id ID do treinador a ser atualizado
     * @return array{message: string, data: \App\Models\Treinador|null}
     */
    public function updateTreinador(int $id, array $data): array
    {
        DB::beginTransaction();

        $treinador = Treinador::findOrFail($id);
        $treinador->update($data);

        DB::commit();

        return  [
            'message' => 'Treinador atualizado',
            'data' => $treinador,
        ];
    }

    /**
     * Remove um treinador do banco de dados
     *
     * @param int $id ID do treinador a ser removido
     * @return array{message: string, data: \App\Models\Treinador|null}
     */
    public function deleteTreinador(int $id)
    {
        $treinador = Treinador::findOrFail($id);
        $treinador->delete();

        return [
            'message' => 'Treinador excluído',
            'data' => $treinador,
        ];
    }
}
