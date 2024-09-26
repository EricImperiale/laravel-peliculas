<?php
/** @var \App\Models\Movie $movie */
?>

@extends('layout.main')

@section('title', "Confirmación Para Eliminar Permanentemente la Película " . $movie->title)

@section('main')
    <x-movie-data :movie="$movie" />

    <hr>

    <form action="{{ route('movies.trashed.processDelete', ['id' => $movie->movie_id]) }}" method="post">
        @csrf
        <h2 class="mb-3">Confirmación Necesaria</h2>

        <p class="mb-3">¿Realmente querés eliminar esta película de forma permanente? Esta acción no puede revertirse.</p>

        <button type="submit" class="btn btn-danger">Sí, eliminar permanentemente</button>
    </form>
@endsection
