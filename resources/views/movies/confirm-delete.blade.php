<?php
/** @var \App\Models\Movie $movie */
?>

@extends('layout.main')

@section('title', "Confirmación Para Eliminar la Película " . $movie->title)

@section('main')
    {{--<article class="mb-3">
        <div class="row flex-row-reverse mb-3">
            <div class="col-9">
                <h1 class="mb-3">{{ $movie->title }}</h1>

                <dl>
                    <dt>Precio</dt>
                    <dd>$ {{ $movie->price }}</dd>
                    <dt>Fecha de Estreno</dt>
                    <dd>{{ $movie->release_date }}</dd>
                </dl>
            </div>
            <div class="col-3">
                <p>Acá va a ir la portada...</p>
                --}}{{--<img src="{{ $movie->cover }}" alt="{{ $movie->cover_description }}">--}}{{--
            </div>
        </div>

        <h2 class="mb-3">Sinopsis</h2>
        {{ $movie->synopsis }}
    </article>--}}
{{--    @include('movies._movie-data')--}}
    <x-movie-data :movie="$movie" />

    <hr>

    <form action="{{ route('movies.processDelete', ['id' => $movie->movie_id]) }}" method="post">
        @csrf
        <h2 class="mb-3">Confirmación Necesaria</h2>

        <p class="mb-3">¿Realmente querés eliminar esta película?</p>

        <button type="submit" class="btn btn-danger">Sí, eliminar</button>
    </form>
@endsection
