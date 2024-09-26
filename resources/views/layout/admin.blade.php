<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Panel de Administración :: DV Películas</title>
    <link rel="stylesheet" href="{{ url('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/app.css') }}">
</head>
<body>
    <div id="app">
        <header>
            <p>Panel de Administración</p>
        </header>
        <main class="container-fluid py-3">
            <div class="row">
                <div class="col-2">
                    <nav>
                        <ul>
                            <li><a href="#">Tablero</a></li>
                            <li><a href="#">Películas</a></li>
                            <li><a href="#">Usuarios</a></li>
                            <li><a href="#">Ir al Sitio</a></li>
                            <li><a href="#">Cerrar Sesión</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-10">
                    @yield('main')
                </div>
            </div>
        </main>
        <footer class="footer">
            <p>Da Vinci &copy; 2023</p>
        </footer>
    </div>
</body>
</html>
