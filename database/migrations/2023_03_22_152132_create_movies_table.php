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
        // La clase Schema de Laravel permite interactuar con el schema de la base de datos, por ejemplo
        // para crear, borrar o editar tablas, vistas, etc.
        // El método "create" permite crear una tabla.
        // Recibe 2 argumentos:
        // 1. String. El nombre de la tabla.
        // 2. Closure. La función a ejecutar para crear la tabla. Esta función, generalmente, va a pedir
        //      que se le pase como argumento una instancia de "Blueprint".
        // Blueprint ("plano de construcción" en inglés) es la clase que permite definir la estructura
        // de una tabla. Tiene múltiples métodos para lograr este objetivo.
        Schema::create('movies', function (Blueprint $table) {
            // El método "id" crea una columna que es:
            //  BIGINT PK NOT NULL UNSIGNED AUTO_INCREMENT
            // Por defecto, Laravel pone como nombre "id" a las columnas para el id.
            // Si queremos cambiarlo, solamente necesitamos pasar como argumento el nuevo nombre.
            $table->id('movie_id');
            // Para crear columnas de tipo VARCHAR o equivalente, tenemos el método "string".
            $table->string('title', 100);
            // El precio lo vamos a guardar como una columna de tipo INT, en vez de DECIMAL.
            // ¿Por qué un INT para el precio?
            // Si bien teniendo que en cuenta que el precio puede tener decimales, la elección de INT
            // como tipo de dato puede parecer extraña, hay una buena razón para esto.
            // La forma más precisa de operar aritméticamente en informática es con enteros, y no con
            // decimales.
            // Para manejar el tema de los centavos, lo que hacemos es guardar el precio, precisamente,
            // en centavos.
            // O sea, que si el precio fueran "19.99", el precio que guardo sería "1999".
            $table->unsignedInteger('price');
            // Para guardar una fecha podemos usar el campo date().
            $table->date('release_date');
            // Para los campos TEXT, tenemos métodos con nombres equivalentes.
            $table->text('synopsis');
            // Para indicar que una columna puede ser null, tenemos que agregar la llamada al método
            // "nullable".
            $table->string('cover', 255)->nullable();
            $table->string('cover_description', 255)->nullable();

            // El método "timestamps" agrega dos columnas más que los modelos de Eloquent esperan encontrar:
            // - created_at
            // - updated_at
            // Ambas son columnas de tipo TIMESTAMP.
            // Eloquent utiliza esas columnas automáticamente para guardar la fecha de creación de un
            // registro, así como la fecha de última actualización.
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
