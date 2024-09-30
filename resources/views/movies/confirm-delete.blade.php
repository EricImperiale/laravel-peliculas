<?php
/** @var \App\Models\Movie $movie */
?>

@extends('layout.main')

@section('title', "Confirmación Para Eliminar la Película " . $movie->title)

@section('main')
    <x-movie-data :movie="$movie" />

    <hr>

    <form action="{{ route('movies.processDelete', ['id' => $movie->movie_id]) }}" method="post">
        @csrf
        <h2 class="mb-3">Confirmación Necesaria</h2>

        <p class="mb-3">¿Realmente querés eliminar esta película?</p>

        <button type="submit" class="btn btn-danger">Sí, eliminar</button>
    </form>
@endsection
