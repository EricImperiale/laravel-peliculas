<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Todas las clases de migraciones tienen 2 métodos:
 * - up
 *  Lleva las instrucciones de lo que queremos realizar. Por ejemplo, crear una tabla, agregar un campo,
 *      eliminar un campo, etc.
 * - down
 *  Lleva las instrucciones opuestas a las que hicimos en el "up". La idea de las migrations es que sean
 *      reversibles. Es decir, tienen que poder decir lo que hacen, y cómo deshacer esos cambios.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id('movie_id');
            $table->string('title', 100);
            $table->unsignedInteger('price');
            $table->date('release_date');
            $table->text('synopsis');
            $table->string('cover', 255)->nullable();
            $table->string('cover_description', 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
