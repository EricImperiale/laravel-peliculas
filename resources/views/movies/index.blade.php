<?php
// Como las vistas reciben los valores "mágicamente", los editores pueden tener problemas en saber que
// las variables están disponibles.
// Para solventar este inconveniente, es una buena idea documentar las variables con phpDoc.
/** @var \App\Models\Movie[]|\Illuminate\Database\Eloquent\Collection $movies */
/** @var \App\Searches\MovieSearchParams $searchParams */
?>
@extends('layout.main')

@section('title', 'Listado de Películas')

@section('main')
<h1 class="mb-3">Películas</h1>

@auth
<div class="mb-4">
    <div><a href="{{ route('movies.formNew') }}">Publicar una Nueva Película</a></div>
    <div><a href="{{ route('movies.trashed.index') }}">Ver Películas Eliminadas</a></div>
</div>
@endauth

<section class="mb-4">
    <h2 class="mb-2">Buscador</h2>

    <!-- TODO: Hacer componente -->
    <form action="{{ route('movies.index') }}" method="get">
        <div class="mb-3">
            <label for="searchTitle" class="form-label">Título</label>
            <input
                type="search"
                id="searchTitle"
                name="t"
                class="form-control"
                value="{{ $searchParams->getTitle() }}"
            >
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
</section>

<h2 class="visually-hidden">Lista de Productos</h2>

@if($searchParams->getTitle())
    <p class="mb-3">Se muestran los resultados para la búsqueda del título <b>{{ $searchParams->getTitle() }}</b>.</p>
@endif

@if($movies->isNotEmpty())
    <x-table :model="$movies"/>
@else
    <p>No hay películas para mostrar.</p>
@endif
@endsection
