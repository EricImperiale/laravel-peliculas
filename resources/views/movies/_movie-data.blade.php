<?php
/** @var \App\Models\Movie $movie */
?>
<article class="mb-3">
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
            <img src="{{ $movie->cover }}" alt="{{ $movie->cover_description }}">
        </div>
    </div>

    <h2 class="mb-3">Sinopsis</h2>
    {{ $movie->synopsis }}
</article>
