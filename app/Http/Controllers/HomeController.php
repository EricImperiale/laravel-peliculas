<?php

namespace App\Http\Controllers;

// Los Controllers deben "heredar" de la clase Controller base de Laravel.
class HomeController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function about()
    {
        return view('about');
    }
}
