<?php

namespace Database\Seeders;

use App\Models\Treinador;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TreinadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!Treinador::where('email', 'gildoncios@gmail.com')->first()){
            Treinador::create([
                'nome' => 'Gildo',
                'email' => 'gildoncios@gmail.com',
                'regiao' => 'Kanto',
                'tipo_favorito' => 'Fantasma',
                'idade' => 20
            ]);
        }

        if(!Treinador::where('email', 'apreSilva@gmail.com')->first()){
            Treinador::create([
                'nome' => 'Apre',
                'email' => 'apreSilva@gmail.com',
                'regiao' => 'Unova',
                'tipo_favorito' => 'Agua',
                'idade' => 15
            ]);
        }
        if(!Treinador::where('email', 'luanMartins@gmail.com')->first()){
            Treinador::create([
                'nome' => 'Luan',
                'email' => 'luanMartins@gmail.com',
                'regiao' => 'Kanto',
                'tipo_favorito' => 'Fogo',
                'idade' => 20
            ]);
        }

        if(!Treinador::where('email', 'fernandaSantos@gmail.com')->first()){
            Treinador::create([
                'nome' => 'Fernanda',
                'email' => 'fernandaSantos@gmail.com',
                'regiao' => 'Johto',
                'tipo_favorito' => 'Planta',
                'idade' => 18
            ]);
        }

        if(!Treinador::where('email', 'ricardoLima@gmail.com')->first()){
            Treinador::create([
                'nome' => 'Ricardo',
                'email' => 'ricardoLima@gmail.com',
                'regiao' => 'Hoenn',
                'tipo_favorito' => 'Terra',
                'idade' => 22
            ]);
        }

    }
}
