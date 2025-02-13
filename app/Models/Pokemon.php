<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    protected $table = 'pokemons';

    protected $fillable = [
        "nome",
        "ataque",
        "defesa",
        "vida",
        "vida_atual",
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
