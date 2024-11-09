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
            $table->id('user_id');
            $table->string('email', 255)->unique();
            $table->string('password', 255)->nullable();
            $table->string('nickname', 255)->nullable();
            $table->boolean('isAdmin')->default(false);
            $table->unsignedInteger('provider_id')->nullable();
            $table->string('provider_token', 255)->nullable();
            $table->string('provider_refresh_token', 255)->nullable();
            $table->rememberToken();
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
