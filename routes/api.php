<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Todas las rutas tiene automáticamente el prefijo "api/".
Route::get('/movies', [\App\Http\Controllers\API\MoviesAdminController::class, 'index'])
    ->middleware(['auth']);
Route::get('/movies/{id}', [\App\Http\Controllers\API\MoviesAdminController::class, 'view'])
    ->middleware(['auth']);

Route::post('/movies', [\App\Http\Controllers\API\MoviesAdminController::class, 'create'])
    ->middleware(['auth']);
