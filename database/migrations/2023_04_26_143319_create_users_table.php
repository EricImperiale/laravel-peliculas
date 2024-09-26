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
        Schema::create('users', function (Blueprint $table) {
            // Para crear una tabla de usuarios qeu sea compatible con el módulo de autenticación de
            // Laravel, tenemos que asegurarnos de que tenga lo siguiente:
            // - Un campo que sirva para identificar y buscar usuarios, como el "email".
            // - Un campo llamado "password" para almacenar el password, de tipo VARCHAR de al menos 60
            //  caracteres (255 recomendados).
            // - Una columna "remember_token" de tipo VARCHAR(100) y NULL. Por más que no usemos la opción
            //  de "recordarme" es necesario que este campo esté.
            $table->id('user_id');
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->rememberToken(); // Agrega el campo "remember_token"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
