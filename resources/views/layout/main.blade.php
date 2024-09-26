{{-- Los "layouts" sería nuestros templates "madre". Sirven de base para los otros templates. --}}
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
    {{--
    En general, cuando usamos URLs amigables, se recomienda evitar usar rutas relativas para
    vincular archivos o hacer links.
    Esto se debe a que las URLs amigables pueden tener cualquier cantidad arbitraria de segmentos.
    Usar rutas absolutas nos permite evitarnos todos los dolores de cabeza asociados con esto.
    Pero tienen un inconveniente: las rutas absolutas cambian dependiendo de donde esté alojada la web.
    Para solventar ese inconveniente, Laravel nos provee de una función url() que genera rutas absolutas
    a partir de la carpeta raíz del proyecto ("public"), de manera dinámica.
    --}}
    {{--    <link rel="stylesheet" href="css/bootstrap.min.css">--}}
    {{--    <link rel="stylesheet" href="css/app.css">--}}
    <link rel="stylesheet" href="{{ url('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/app.css') }}">
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

                        {{-- El método Auth::check() retorna true o false indicando si el usuario está autenticado o no. --}}
{{--                        @if(auth()->check())--}}
                        {{-- Como es muy común tener que poner condicionales en el template para renderizar
                         un contenido si el usuario está autenticadao, u otro si no lo está, Laravel nos
                         brinda un directiva @auth para hacerlo más claro. --}}
                        @auth
                        <li class="nav-item">
                            <form action="{{ route('auth.processLogout') }}" method="post">
                                @csrf
                                {{-- Auth::user() retorna el usuario autenticado, si existe, o null. --}}
                                <button type="submit" class="btn nav-link">{{ auth()->user()->email }} (Cerrar Sesión)</button>
                            </form>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('auth.formLogin') }}">Iniciar Sesión</a>
                        </li>
                        @endauth
                        {{--                        @endif--}}
                    </ul>
                </div>
            </div>
        </nav>
        <main class="container py-3">
            {{--
            Preguntamos si hay mensajes de feedback que mostrar.
            Session es la clase de Laravel para manejar las sesiones.
            @if(Session::has('status.message'))
                <div class="alert alert-{{ Session::get('status.type') === 'error' ? 'danger' : 'success' }}">{!! Session::get('status.message') !!}</div>
            @endif
            --}}

            <x-auth-session type="{{ Session::get('status.type') }}" />

            {{--
            La directiva @yield permite "ceder" este espacio a los templates que vayan a extender de éste.
            Solo debemos ponerle un nombre/id como parámetro para que puedan identificarlo.
            --}}
            @yield('main')
        </main>
        <footer class="footer">
            <p>Da Vinci &copy; 2023</p>
        </footer>
    </div>
</body>
</html>
