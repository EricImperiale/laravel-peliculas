<?php
/** @var \App\Models\Movie[]|\Illuminate\Database\Eloquent\Collection $movies */
?>
@extends('layout.main')

@section('title', 'Películas Eliminadas')

@section('main')
<h1 class="mb-3">Películas Eliminadas</h1>

<div class="mb-3">
    <a href="{{ route('movies.index') }}">Ver Las Películas</a>
</div>

@if($movies->count() > 0)
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Título</th>
        <th>Fecha de Estreno</th>
        <th>Precio</th>
        <th>Sinopsis</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    @foreach($movies as $movie)
    <tr>
        <td>{{ $movie->title }}</td>
        <td>{{ $movie->release_date }}</td>
        <td>${{ $movie->price }}</td>
        <td>{{ $movie->synopsis }}</td>
        <td>
            <div class="d-flex gap-1">
                {{--<a href="{{ route('movies.view', ['id' => $movie->movie_id]) }}" class="btn btn-primary">Ver</a>
                <a href="{{ route('movies.formUpdate', ['id' => $movie->movie_id]) }}" class="btn btn-primary">Editar</a>--}}
                <a href="{{ route('movies.trashed.confirmDelete', ['id' => $movie->movie_id]) }}" class="btn btn-danger">Eliminar</a>
            </div>
        </td>
    </tr>
    @endforeach

    <?php
    /*foreach($movies as $movie):
    ?>
    <tr>
        <td>{{ $movie->title }}</td>
        <td>{{ $movie->release_date }}</td>
        <td>$ {{ $movie->price }}</td>
        <td>{{ $movie->synopsis }}</td>
        <td></td>
    </tr>
    <?php
    endforeach;*/
    ?>
    </tbody>
</table>
@else
    <p>¡Hurra! No hay películas eliminadas.</p>
@endif
@endsection
