<?php
/** @var \App\Models\Movie $movie */
?>

@extends('layout.main')

@section('title', $movie->title)

@section('main')
    <x-movie-data :movie="$movie" />
@endsection
