<?php

namespace App\Jobs;

use App\Models\Treinador;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessTradePokemon implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Treinador $treinador1,
        public Treinador $treinador2
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       Log::info('Processando troca de Pokémon para os treinadores', [
        'treinador1' => $this->treinador1->id,
        'treinador2' => $this->treinador2->id
    ]);
    }
}
