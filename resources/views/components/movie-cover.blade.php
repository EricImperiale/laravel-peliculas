<?php
use Illuminate\Support\Facades\Storage;
/** @var \App\Models\Movie $movie */
/** @var string|null $alt */
?>

<div>
    <img src="{{ Storage::url('imgs/' . $movie->cover) }}" alt="{{ $alt ?? $movie->cover_description }}" class="mw-100">
</div>
