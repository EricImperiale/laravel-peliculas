<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            // Creamos el campo para la FK.
            $table->unsignedSmallInteger('country_id')->after('movie_id');

            // Definimos la FK.
            $table->foreign('country_id')->references('country_id')->on('countries');

            // Si la FK fuese un BIGINT en vez de un SMALLINT, podríamos utilizar la sintaxis abreviada
            // para la creación de la FK:
//            $table->foreignId('country_id')->constrained('countries', 'country_id');
            // Esto reemplaza a ambas instrucciones anteriores, tanto la creación de la columna como la
            // definición de la FK.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            //
        });
    }
};
