<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
 |--------------------------------------------------------------------------
 | Cómo funcionan las rutas
 |--------------------------------------------------------------------------
 | Como nos dice el comentario de arriba, en este archivo podemos registrar
 | las "rutas" de nuestra aplicación.
 | Una "ruta" es una URL (a partir de la raíz del sitio) a la cual le
 | asociamos algún funcionamiento y un verbo HTTP.
 |
 | Las rutas se registran usando la clase Illuminate\Support\Facades\Route de
 | Laravel. Esta clase tiene métodos para cada uno de los verbos de HTTP, por
 | ejemplo:
 | - get(url, action)
 | - post(url, action)
 | - put(url, action)
 | - patch(url, action)
 | - delete(url, action)
 | - options(url, action)
 |
 | Cada uno de esos métodos registra una ruta que puede ser accedida solamente
 | con ese verbo.
 |
 | Por ejemplo, vemos que Laravel nos muestra por defecto una ruta:
 |
 | Route::get('/', function () {
 |     return view('welcome');
 | });
 |
 | Esa instrucción está definiendo una ruta por GET para la URL raíz del sitio.
 | Y le dice que cuando se acceda a la misma, debe ejecutarse la _acción_
 | indicada en el segundo parámetro.
 |
 | La acción puede ser:
 | - Closure.
 | - Array que asocie el método de una clase, típicamente un método de un
 |  Controller.
 |
 | El ejemplo de la ruta de recién utiliza un Closure. Esto sirve para pruebas
 | rápidas, pero no es una buena idea para proyectos en serio. El motivo es que
 | no queremos llenar de lógica este archivo.
 | Piensen que vamos a tener decenas o cientos de rutas, o incluso miles de
 | rutas definidas en este archivo. Si todas tienen la lógica extra de qué
 | renderizar, va a volver un caos inmantenible rápidamente.
 | Por eso, se prefiere usar Controllers para organizar estas acciones.
 | Los Controllers son en esencia clases que contienen métodos que sirven como
 | acciones para las rutas.
 | Los Controllers se guardan, por defecto, en la carpeta [app/Http/Controllers].
 */

//Route::get('/', function () {
//    // Le pedimos a Laravel que imprima la vista "welcome", con ayuda de la función view().
//    // ¿Dónde está esa vista?
//    // En Laravel, todas las vistas (templates de HTML a renderizar) están ubicadas en la
//    // carpeta [resources/views].
//    // La función view() recibe como string el nombre del archivo de una vista, ubicada en
//    // esa carpeta, sin la extensión [.blade.php] o [.php].
////    return view('welcome');
//    return view('home');
//});

//Route::get('quienes-somos', function() {
//    return view('about');
//});

//Route::get('peliculas/listado', function() {
////     Para referenciar a una vista -en una subcarpeta, podemos separar con "/" o con ".".
////    return view('movies/index');
//    return view('movies.index');
//});

// Para asociar el método de un Controller a la ruta, pasamos un array con 2 valores:
// 1. El FQN de la clase del Controller.
// 2. El nombre del método.
Route::get('/', [\App\Http\Controllers\HomeController::class, 'home'])
    ->name('home');
Route::get('quienes-somos', [\App\Http\Controllers\HomeController::class, 'about'])
    ->name('about');

// Auth
Route::get('iniciar-sesion', [\App\Http\Controllers\AuthController::class, 'formLogin'])
    ->name('auth.formLogin');
Route::post('iniciar-sesion', [\App\Http\Controllers\AuthController::class, 'processLogin'])
    ->name('auth.processLogin');
Route::post('cerrar-sesion', [\App\Http\Controllers\AuthController::class, 'processLogout'])
    ->name('auth.processLogout');

/*
 |--------------------------------------------------------------------------
 | Películas
 |--------------------------------------------------------------------------
 */
Route::group(['middleware' => 'auth'], function () {
    Route::group([
        'prefix' => 'peliculas',
        'as' => 'movies.',
    ], function () {
        Route::get('peliculas/listado', [\App\Http\Controllers\MoviesController::class, 'index'])
            ->name('index');

        Route::get('peliculas/eliminadas', [\App\Http\Controllers\MoviesTrashedController::class, 'index'])
            ->name('trashed.index');

// Generalmente, las rutas que manejan las peticiones de un formulario, suelen llamarse exactamente igual
// que las rutas que muestran el formulario. La única diferencia es el método HTTP que usamos (GET vs
// POST).
        Route::get('peliculas/nueva', [\App\Http\Controllers\MoviesController::class, 'formNew'])
            ->name('formNew');
        Route::post('peliculas/nueva', [\App\Http\Controllers\MoviesController::class, 'processNew'])
            ->name('processNew');

// Agregamos la ruta para el detalle de la película.
// Este tipo de rutas, generalmente, llevan el formato de:
//  peliculas/1
// Es decir, `nombre-del-recurso/identificador`
// En este caso, el recurso es "peliculas", y el identificador es el id "1", o el que toque.
// El identificador, por supuesto, es variable. Puede ser cualquier valor que corresponda con algún ID
// que exista en la tabla.
// Para definir "segmentos dinámicos" de la URL, podemos usar la sintaxis {variable}, donde `variable`
// sería el nombre del parámetro que queremos generar para la acción.
        Route::get('peliculas/{id}', [\App\Http\Controllers\MoviesController::class, 'view'])
            ->name('view')
            ->middleware(['mayoria-de-edad']);

        Route::get('peliculas/{id}/confirmar-edad', [\App\Http\Controllers\ConfirmAgeController::class, 'formConfirmation'])
            ->name('confirm-age.formConfirmation');
        Route::post('peliculas/{id}/confirmar-edad', [\App\Http\Controllers\ConfirmAgeController::class, 'processConfirmation'])
            ->name('confirm-age.processConfirmation');

        Route::post('peliculas/{id}/reservar', [\App\Http\Controllers\MoviesReservationController::class, 'processReservation'])
            ->name('processReservation');

        Route::get('peliculas/{id}/editar', [\App\Http\Controllers\MoviesController::class, 'formUpdate'])
            ->name('formUpdate')
            ->middleware(['auth']);
        Route::post('peliculas/{id}/editar', [\App\Http\Controllers\MoviesController::class, 'processUpdate'])
            ->name('processUpdate');

        Route::get('peliculas/{id}/eliminar', [\App\Http\Controllers\MoviesController::class, 'confirmDelete'])
            ->name('confirmDelete');
        Route::post('peliculas/{id}/eliminar', [\App\Http\Controllers\MoviesController::class, 'processDelete'])
            ->name('processDelete');


        Route::get('peliculas/eliminadas/{id}/eliminar', [\App\Http\Controllers\MoviesTrashedController::class, 'confirmDelete'])
            ->name('movies.trashed.confirmDelete');
        Route::post('peliculas/eliminadas/{id}/eliminar', [\App\Http\Controllers\MoviesTrashedController::class, 'processDelete'])
            ->name('trashed.processDelete');
    });
});

Route::get('admin', [\App\Http\Controllers\AdminController::class, 'dashboard'])
    ->name('admin.dashboard');
