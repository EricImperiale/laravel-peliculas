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
        Schema::create('movies_has_genres', function (Blueprint $table) {
            $table->foreignId('movie_id')->constrained('movies', 'movie_id');

            $table->unsignedTinyInteger('genre_id');
            $table->foreign('genre_id')->references('genre_id')->on('genres');

            $table->timestamps();

            $table->primary(['movie_id', 'genre_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies_has_genres');
    }
};
