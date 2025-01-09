<?php

namespace Database\Seeders;

use App\Models\Pokemon;
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

        $pikachu = Pokemon::where('nome', 'Pikachu')->where('shiny', false)->first();
        $Charmander = Pokemon::where('nome', 'Charmander')->where('shiny', true)->first();
        $Jigglypuff = Pokemon::where('nome', 'Jigglypuff')->where('shiny', false)->first();
        $Squirtle = Pokemon::where('nome', 'Squirtle')->where('shiny', true)->first();
        $Bulbasaur = Pokemon::where('nome', 'Bulbasaur')->where('shiny', false)->first();

        if(!Treinador::where('email', 'gildoncios@gmail.com')->first()){
            Treinador::create([
                'nome' => 'Gildo',
                'email' => 'gildoncios@gmail.com',
                'regiao' => 'Kanto',
                'tipo_favorito' => 'Fantasma',
                'idade' => 20,
                'pokemon_id' => $pikachu->id ?? null,
            ]);
        }

        if(!Treinador::where('email', 'apreSilva@gmail.com')->first()){
            Treinador::create([
                'nome' => 'Apre',
                'email' => 'apreSilva@gmail.com',
                'regiao' => 'Unova',
                'tipo_favorito' => 'Agua',
                'idade' => 15,
                'pokemon_id' => $Jigglypuff->id ?? null,
            ]);
        }
        if(!Treinador::where('email', 'luanMartins@gmail.com')->first()){
            Treinador::create([
                'nome' => 'Luan',
                'email' => 'luanMartins@gmail.com',
                'regiao' => 'Kanto',
                'tipo_favorito' => 'Fogo',
                'idade' => 20,
                'pokemon_id' => $Squirtle->id ?? null,
            ]);
        }

        if(!Treinador::where('email', 'fernandaSantos@gmail.com')->first()){
            Treinador::create([
                'nome' => 'Fernanda',
                'email' => 'fernandaSantos@gmail.com',
                'regiao' => 'Johto',
                'tipo_favorito' => 'Planta',
                'idade' => 18,
                'pokemon_id' => $Bulbasaur->id ?? null
            ]);
        }

        if(!Treinador::where('email', 'ricardoLima@gmail.com')->first()){
            Treinador::create([
                'nome' => 'Ricardo',
                'email' => 'ricardoLima@gmail.com',
                'regiao' => 'Hoenn',
                'tipo_favorito' => 'Terra',
                'idade' => 22,
                'pokemon_id' => $Charmander->id ?? null
            ]);
        }

    }
}
