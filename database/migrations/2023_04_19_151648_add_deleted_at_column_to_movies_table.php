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
        // Schema::table() es el método que sirve para modificar una tabla existente.
        Schema::table('movies', function (Blueprint $table) {
            // Por defecto, la columna para soft delete en Laravel debe llamarse "deleted_at" y ser de
            // tipo TIMESTAMP NULL.
//            $table->timestamp('deleted_at')->nullable();

            // Opcionalmente, podemos crearla más fácilmente con el método especial de "softDeletes".
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
