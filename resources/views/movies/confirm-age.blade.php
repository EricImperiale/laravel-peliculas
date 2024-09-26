<?php
/** @var \App\Models\Movie $movie */
?>

@extends('layout.main')

@section('title', 'Se Requiere Confirmación de Mayoría de Edad')

@section('main')
    <h1 class="mb-3">Se Requiere Confirmación Para Ver Esta Película</h1>

    <p>Esta película está indicada solo para mayores de 18 años. Para verla, necesitamos que confirmes que cumplís con este requerimiento.</p>

    <form action="{{ route('confirm-age.processConfirmation', ['id' => $id]) }}" method="post">
        @csrf
        <button type="submit" class="btn btn-primary">Sí, soy mayor de edad</button>
        <a href="{{ route('movies.index') }}" class="btn btn-danger">No, soy menor. ¡Sacame de acá!</a>
    </form>
@endsection
