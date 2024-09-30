<?php
/** @var \Illuminate\Support\ViewErrorBag $errors */
/** @var \App\Models\Movie $movie */
/** @var \App\Models\Country[]|\Illuminate\Database\Eloquent\Collection $countries */
/** @var \App\Models\Classification[]|\Illuminate\Database\Eloquent\Collection $classifications */
/** @var \App\Models\Genre[]|\Illuminate\Database\Eloquent\Collection $genres */
?>
@extends('layout.main')

@section('title', 'Editar la Película ' . $movie->title)

@section('main')
    <h1>Editar '{{ $movie->title}}'</h1>

    <form action="{{ route('movies.processUpdate', ['id' => $movie->movie_id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Título</label>
            <input
                type="text"
                id="title"
                name="title"
                class="form-control"
                @if($errors->has('title')) aria-describedby="error-title" @endif
                value="{{ old('title', $movie->title) }}"
            >
            @if($errors->has('title'))
                <div class="mt-2 text-danger" id="error-title">{{ $errors->first('title') }}</div>
            @endif
        </div>
        <div class="mb-3">
            <label for="release_date" class="form-label">Fecha de Estreno</label>
            <input
                type="date"
                id="release_date"
                name="release_date"
                class="form-control"
                @error('release_date') aria-describedby="error-release_date" @enderror
                value="{{ old('release_date', $movie->release_date) }}"
            >
            @error('release_date')
                <div class="mt-2 text-danger" id="error-release_date">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Precio</label>
            <input
                type="text"
                id="price"
                name="price"
                class="form-control"
                @error('price') aria-describedby="error-price" @enderror
                value="{{ old('price', $movie->price) }}"
            >
            @error('price')
                <div class="mt-2 text-danger" id="error-price">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="country_id" class="form-label">País de Origen</label>
            <select
                name="country_id"
                id="country_id"
                class="form-control"
                @error('country_id') aria-describedby="error-country_id" @enderror
            >
                <option value=""></option>
                @foreach($countries as $country)
                    <option
                        value="{{ $country->country_id }}"
                        @selected(old('country_id', $movie->country_id) == $country->country_id)
                    >{{ $country->name }}</option>
                @endforeach
            </select>
            @error('country_id')
                <div class="mt-2 text-danger" id="error-country_id">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="classification_id" class="form-label">Clasificación</label>
            <select
                name="classification_id"
                id="classification_id"
                class="form-control"
                @error('classification_id') aria-describedby="error-classification_id" @enderror
            >
                <option value=""></option>
                @foreach($classifications as $classification)
                    <option
                        value="{{ $classification->classification_id }}"
                        @selected(old('classification_id', $movie->classification_id) == $classification->classification_id)
                    >{{ $classification->name }}</option>
                @endforeach
            </select>
            @error('classification_id')
            <div class="mt-2 text-danger" id="error-classification_id">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="synopsis" class="form-label">Sinopsis</label>
            <textarea
                id="synopsis"
                name="synopsis"
                class="form-control"
                @error('synopsis') aria-describedby="error-synopsis" @enderror
            >{{ old('synopsis', $movie->synopsis) }}</textarea>
            @error('synopsis')
                <div class="mt-2 text-danger" id="error-synopsis">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <p>Portada Actual</p>
            <x-movie-cover :movie="$movie" alt="" />
        </div>
        <div class="mb-3">
            <label for="cover" class="form-label">Portada</label>
            <input
                type="file"
                id="cover"
                name="cover"
                class="form-control"
                @error('cover') aria-describedby="error-cover" @enderror
            >
            @error('cover')
                <div class="mt-2 text-danger" id="error-cover">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="cover_description" class="form-label">Descripción de la Portada</label>
            <input
                type="text"
                id="cover_description"
                name="cover_description"
                class="form-control"
                @error('cover_description') aria-describedby="error-cover_description" @enderror
                value="{{ old('cover_description', $movie->cover_description) }}"
            >
            @error('cover_description')
                <div class="mt-2 text-danger" id="error-cover_description">{{ $message }}</div>
            @enderror
        </div>

        <fieldset>
            <legend>Géneros</legend>

            <div class="mb-3">
                @foreach($genres as $genre)
                    <div class="form-check form-check-inline">
                        <label>
                            <input
                                type="checkbox"
                                name="genre_id[]"
                                value="{{ $genre->genre_id }}"
                                class="form-check-input"
                                @checked(in_array($genre->genre_id, old('genre_id', $movie->getGenreIds())))
                            >
                            {{ $genre->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </fieldset>

        <button type="submit" class="btn btn-primary">Publicar</button>
    </form>
@endsection
