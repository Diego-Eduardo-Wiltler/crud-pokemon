<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treinador extends Model
{
    protected $table = 'treinadores';

    protected $fillable = [
        "nome",
        'pokemon_id',
        "email",
        "regiao",
        "tipo_favorito",
        "idade"


    ];
    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class);
    }
}
