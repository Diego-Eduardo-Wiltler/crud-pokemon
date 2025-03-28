<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreinadorTrade extends Model
{
    protected $fillable = [
        'pokemon_id',
        'old_trainer_id',
        'new_trainer_id',
        'traded_at'
    ];

    protected $casts = [
        'traded_at' => 'datetime',
    ];

    public function newTrainer()
    {
        return $this->belongsTo(Treinador::class, 'new_trainer_id');
    }
}
