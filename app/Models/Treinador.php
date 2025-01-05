<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treinador extends Model
{
    protected $table = 'treinadores';

    protected $fillable = [
        "nome",
        "email",
        "regiao",
        "tipo_favorito",
        "idade"

    ];
}
