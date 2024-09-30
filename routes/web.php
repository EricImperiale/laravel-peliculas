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
Route::get('peliculas/listado', [\App\Http\Controllers\MoviesController::class, 'index'])
    ->name('movies.index');
Route::get('peliculas/{id}', [\App\Http\Controllers\MoviesController::class, 'view'])
    ->name('movies.view')
    ->middleware(['mayoria-de-edad']);


Route::group(['middleware' => 'auth'], function () {
    Route::group([
        'prefix' => 'peliculas',
        'as' => 'movies.',
    ], function () {
        Route::get('peliculas/eliminadas', [\App\Http\Controllers\MoviesTrashedController::class, 'index'])
            ->name('trashed.index');

        Route::get('peliculas/nueva', [\App\Http\Controllers\MoviesController::class, 'formNew'])
            ->name('formNew');
        Route::post('peliculas/nueva', [\App\Http\Controllers\MoviesController::class, 'processNew'])
            ->name('processNew');

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
