<?php
use Illuminate\Support\Facades\Session;

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') :: DV Películas</title>
    <link rel="stylesheet" href="{{ url('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('home') }}">DV Películas</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Abrir/cerrar menú de navegación">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Home</a>
                            <!-- <a class="nav-link active" aria-current="page" href="#">Home</a> -->
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('about') }}">Quiénes Somos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('movies.index') }}">Películas</a>
                        </li>

                        @auth
                        <li class="nav-item">
                            <form action="{{ route('auth.processLogout') }}" method="post">
                                @csrf
                                <button type="submit" class="btn nav-link">{{ auth()->user()->email }} (Cerrar Sesión)</button>
                            </form>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('auth.formLogin') }}">Iniciar Sesión</a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
        <main class="container py-3">
            <x-auth-session type="{{ Session::get('status.type') }}" />

            @yield('main')
        </main>
        <footer class="footer">
            <p>Da Vinci &copy; 2023</p>
        </footer>
    </div>
</body>
</html>
