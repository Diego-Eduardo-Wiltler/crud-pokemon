<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treinador extends Model
{
    use HasFactory;

    protected $table = 'treinadores';

    protected $fillable = [
        "nome",
        "email",
        "regiao",
        "tipo_favorito",
        "idade",
        "pokemon_id"
    ];

    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class);
    }

    public function latestTrade()
    {
        return $this->hasOne(TreinadorTrade::class, 'new_trainer_id')
                    ->latest('traded_at');
    }
}
