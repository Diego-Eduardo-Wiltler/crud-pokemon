<?php

namespace Database\Seeders;

use App\Models\Pokemon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PokemonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Pokemon::where('nome', 'pikachu')->where('shiny', false)->first()) {
            Pokemon::create([
                'nome' => 'Pikachu',
                'tipo' => 'Eletrico',
                'peso' => '6Kg',
                'localizacao' => 'Area selvagem - Rota 4',
                'shiny' => false,
            ]);
        }
        if (!Pokemon::where('nome', 'pikachu')->where('shiny', true)->first()) {
            Pokemon::create([
                'nome' => 'Pikachu',
                'tipo' => 'Eletrico',
                'peso' => '6Kg',
                'localizacao' => 'Area selvagem - Rota 4',
                'shiny' => true,
            ]);
        }
        if (!Pokemon::where('nome', 'Charmander')->where('shiny', false)->first()) {
            Pokemon::create([
                'nome' => 'Charmander',
                'tipo' => 'Fogo',
                'peso' => '8.5Kg',
                'localizacao' => 'Área selvagem - Rota 3',
                'shiny' => false,
            ]);
        }
        if (!Pokemon::where('nome', 'Charmander')->where('shiny', true)->first()) {
            Pokemon::create([
                'nome' => 'Charmander',
                'tipo' => 'Fogo',
                'peso' => '8.5Kg',
                'localizacao' => 'Área selvagem - Rota 3',
                'shiny' => true,
            ]);
        }

        if (!Pokemon::where('nome', 'Bulbasaur')->where('shiny', false)->first()) {
            Pokemon::create([
                'nome' => 'Bulbasaur',
                'tipo' => 'Grama/Veneno',
                'peso' => '6.9Kg',
                'localizacao' => 'Área selvagem - Floresta Verde',
                'shiny' => false,
            ]);
        }
        if (!Pokemon::where('nome', 'Bulbasaur')->where('shiny', true)->first()) {
            Pokemon::create([
                'nome' => 'Bulbasaur',
                'tipo' => 'Grama/Veneno',
                'peso' => '6.9Kg',
                'localizacao' => 'Área selvagem - Floresta Verde',
                'shiny' => true,
            ]);
        }


        if (!Pokemon::where('nome', 'Squirtle')->where('shiny', false)->first()) {
            Pokemon::create([
                'nome' => 'Squirtle',
                'tipo' => 'Água',
                'peso' => '9Kg',
                'localizacao' => 'Área selvagem - Praia Azul',
                'shiny' => false,
            ]);
        }
        if (!Pokemon::where('nome', 'Squirtle')->where('shiny', true)->first()) {
            Pokemon::create([
                'nome' => 'Squirtle',
                'tipo' => 'Água',
                'peso' => '9Kg',
                'localizacao' => 'Área selvagem - Praia Azul',
                'shiny' => true,
            ]);
        }

        if (!Pokemon::where('nome', 'Jigglypuff')->where('shiny', false)->first()) {
            Pokemon::create([
                'nome' => 'Jigglypuff',
                'tipo' => 'Normal/Fada',
                'peso' => '5Kg',
                'localizacao' => 'Área selvagem - Caverna do Som',
                'shiny' => false,
            ]);
        }
        if (!Pokemon::where('nome', 'Jigglypuff')->where('shiny', true)->first()) {
            Pokemon::create([
                'nome' => 'Jigglypuff',
                'tipo' => 'Normal/Fada',
                'peso' => '5Kg',
                'localizacao' => 'Área selvagem - Caverna do Som',
                'shiny' => true,
            ]);
        }
    }
}
