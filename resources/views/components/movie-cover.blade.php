<?php
use Illuminate\Support\Facades\Storage;
/** @var \App\Models\Movie $movie */
/** @var string|null $alt */
?>
{{-- Versión con archivos locales --}}
{{--<div>
    @if($movie->cover !== null && file_exists(public_path('imgs/' . $movie->cover)))
        <img src="{{ url('imgs/' . $movie->cover) }}" alt="{{ $alt ?? $movie->cover_description }}" class="mw-100">
    @else
        --}}{{-- Acá deberíamos poner una imagen de "placeholder" que indique que no tenemos una portada. --}}{{--
        <p>No hay portada.</p>
    @endif
</div>--}}
{{-- Versión con API Storage. --}}
<div>
    @if($movie->cover !== null && Storage::has('imgs/' . $movie->cover))
        <img src="{{ Storage::url('imgs/' . $movie->cover) }}" alt="{{ $alt ?? $movie->cover_description }}" class="mw-100">
    @else
        {{-- Acá deberíamos poner una imagen de "placeholder" que indique que no tenemos una portada. --}}
        <p>No hay portada.</p>
    @endif
</div>
