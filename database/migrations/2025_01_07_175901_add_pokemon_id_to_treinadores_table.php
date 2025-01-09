<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('treinadores', function (Blueprint $table) {
            $table->unsignedBigInteger('pokemon_id')->nullable();
            $table->foreign('pokemon_id')->references('id')->on('pokemons')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('treinadores', function (Blueprint $table) {
            $table->dropForeign(['pokemon_id']);
            $table->dropColumn('pokemon_id');
        });
    }
};
