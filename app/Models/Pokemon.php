<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    protected $table = 'pokemons';

    protected $fillable = [
        "nome",
        "tipo",
        "peso",
        "localizacao",
        "shiny",
    ];

    public function treinadores()
    {
        return $this->hasMany(Treinador::class);
    }
}
