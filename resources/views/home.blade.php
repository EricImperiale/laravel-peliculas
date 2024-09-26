{{--
La directiva @extends permite decir que este template "extienda" o "herede" de otro template.
Como argumento, debemos pasar la ruta desde la carpeta [resources/views] al template de base, sin extensión
[.blade.php], y reemplazando las barras por puntos.
--}}
@extends('layout.main')

{{--@section('title') Página Principal @endsection--}}
{{-- Si queremos usar solo un string como contenido del @section, podemos pasarlo como segundo
 parámetro, y nos ahorramos el @endsection. --}}
@section('title', 'Página Principal')

{{--
Una vez extendido un template, tenemos que decirle a nuestros contenidos dónde queremos que se ubiquen.
Es decir, en qué @yield.
Si no lo hacemos, todo el contenido ubicarse antes del template extendido.

Para definir en qué @yield queremos que se imprima, usamos la directiva @section(name) y @endsection
--}}
@section('main')
<h1>Bienvenidos a DV Películas</h1>
@endsection
