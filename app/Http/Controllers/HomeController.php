<?php

namespace App\Http\Controllers;

// Los Controllers deben "heredar" de la clase Controller base de Laravel.
class HomeController extends Controller
{
    public function home()
    {
        // Le pedimos a Laravel que imprima la vista "welcome", con ayuda de la función view().
        // ¿Dónde está esa vista?
        // En Laravel, todas las vistas (templates de HTML a renderizar) están ubicadas en la
        // carpeta [resources/views].
        // La función view() recibe como string el nombre del archivo de una vista, ubicada en
        // esa carpeta, sin la extensión [.blade.php] o [.php].
//    return view('welcome');
        return view('home');
    }

    public function about()
    {
        return view('about');
    }
}
