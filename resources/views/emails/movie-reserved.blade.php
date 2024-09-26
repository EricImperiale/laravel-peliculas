<?php
/** @var \App\Models\Movie $movie */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Película Reservada</title>
</head>
<body>
    <h1>Película Reservada</h1>

    <p>Tu película:</p>

    {{-- Podemos usar componentes o cualquier otro elemento de Blade.
     Tengamos en cuenta que este componente en particular no nos serviría, porque utiliza semántica de HTML
     que no es necesariamente compatible emails, así como estilos con clases, que tampoco son compatibles. --}}
    <x-movie-data :movie="$movie" />

    <p>Fue reservada con éxito.</p>

    <p>Guardá este email para cualquier eventualidad.</p>

    <p>Saludos,<br>
    tus amigos de DV Películas.</p>
</body>
</html>
