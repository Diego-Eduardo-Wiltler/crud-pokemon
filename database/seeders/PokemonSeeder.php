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
        if (!Pokemon::where('nome', 'Pikachu')->where('shiny', false)->first()) {
            Pokemon::create([
                'nome' => 'Pikachu',
                'ataque' => 10,
                'defesa' => 10,
                'vida' => 100,
                'vida_atual' => 100,
                'tipo' => 'Eletrico',
                'peso' => '6Kg',
                'localizacao' => 'Area selvagem - Rota 4',
                'shiny' => false,
            ]);
        }
        if (!Pokemon::where('nome', 'Pikachu')->where('shiny', true)->first()) {
            Pokemon::create([
                'nome' => 'Pikachu',
                'ataque' => 15,
                'defesa' => 5,
                'vida' => 100,
                'vida_atual' => 100,
                'tipo' => 'Eletrico',
                'peso' => '6Kg',
                'localizacao' => 'Area selvagem - Rota 4',
                'shiny' => true,
            ]);
        }
        if (!Pokemon::where('nome', 'Charmander')->where('shiny', false)->first()) {
            Pokemon::create([
                'nome' => 'Charmander',
                'ataque' => 15,
                'defesa' => 12,
                'vida' => 105,
                'vida_atual' => 105,
                'tipo' => 'Fogo',
                'peso' => '8.5Kg',
                'localizacao' => 'Área selvagem - Rota 3',
                'shiny' => false,
            ]);
        }
        if (!Pokemon::where('nome', 'Charmander')->where('shiny', true)->first()) {
            Pokemon::create([
                'nome' => 'Charmander',
                'ataque' => 20,
                'defesa' => 5,
                'vida' => 140,
                'vida_atual' => 140,
                'tipo' => 'Fogo',
                'peso' => '8.5Kg',
                'localizacao' => 'Área selvagem - Rota 3',
                'shiny' => true,
            ]);
        }

        if (!Pokemon::where('nome', 'Bulbasaur')->where('shiny', false)->first()) {
            Pokemon::create([
                'nome' => 'Bulbasaur',
                'ataque' => 10,
                'defesa' => 8,
                'vida' => 110,
                'vida_atual' => 110,
                'tipo' => 'Grama/Veneno',
                'peso' => '6.9Kg',
                'localizacao' => 'Área selvagem - Floresta Verde',
                'shiny' => false,
            ]);
        }
        if (!Pokemon::where('nome', 'Bulbasaur')->where('shiny', true)->first()) {
            Pokemon::create([
                'nome' => 'Bulbasaur',
                'ataque' => 30,
                'defesa' => 10,
                'vida' => 50,
                'vida_atual' => 50,
                'tipo' => 'Grama/Veneno',
                'peso' => '6.9Kg',
                'localizacao' => 'Área selvagem - Floresta Verde',
                'shiny' => true,
            ]);
        }


        if (!Pokemon::where('nome', 'Squirtle')->where('shiny', false)->first()) {
            Pokemon::create([
                'nome' => 'Squirtle',
                'ataque' => 20,
                'defesa' => 2,
                'vida' => 90,
                'vida_atual' => 90,
                'tipo' => 'Água',
                'peso' => '9Kg',
                'localizacao' => 'Área selvagem - Praia Azul',
                'shiny' => false,
            ]);
        }
        if (!Pokemon::where('nome', 'Squirtle')->where('shiny', true)->first()) {
            Pokemon::create([
                'nome' => 'Squirtle',
                'ataque' => 10,
                'defesa' => 5,
                'vida' => 125,
                'vida_atual' => 125,
                'tipo' => 'Água',
                'peso' => '9Kg',
                'localizacao' => 'Área selvagem - Praia Azul',
                'shiny' => true,
            ]);
        }

        if (!Pokemon::where('nome', 'Jigglypuff')->where('shiny', false)->first()) {
            Pokemon::create([
                'nome' => 'Jigglypuff',
                'ataque' => 5,
                'defesa' => 15,
                'vida' => 150,
                'vida_atual' => 150,
                'tipo' => 'Normal/Fada',
                'peso' => '5Kg',
                'localizacao' => 'Área selvagem - Caverna do Som',
                'shiny' => false,
            ]);
        }
        if (!Pokemon::where('nome', 'Jigglypuff')->where('shiny', true)->first()) {
            Pokemon::create([
                'nome' => 'Jigglypuff',
                'ataque' => 10,
                'defesa' => 9,
                'vida' => 140,
                'vida_atual' => 140,
                'tipo' => 'Normal/Fada',
                'peso' => '5Kg',
                'localizacao' => 'Área selvagem - Caverna do Som',
                'shiny' => true,
            ]);
        }
    }
}
