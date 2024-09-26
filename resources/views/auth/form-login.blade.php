@extends('layout.main')

@section('title', 'Iniciar Sesión con mi Cuenta')

@section('main')
<h1 class="mb-3">Ingresar con mi Cuenta</h1>

<form action="{{ route('auth.processLogin') }}" method="post">
    @csrf
    <div class="mb-3">
        <x-input-label for="email" :value="__('form-labels.email')" />
        <x-text-input
            type="email"
            name="email"
            id="email"
            class="form-control"
            value="{{ old('email') }}" />

        <x-input-error :messages="$errors->get('email')" class="mt-2"/>
    </div>
    <div class="mb-3">
        <x-input-label for="password" :value="__('form-labels.password')" />
        <x-text-input
            type="password"
            name="password"
            id="password"
            class="form-control"
            value="{{ old('password') }}" />

        <x-input-error :messages="$errors->get('password')" class="mt-2"/>
    </div>

    <x-primary-button class="mt-3 w-100">
        {{ __('form-labels.login') }}
    </x-primary-button>
</form>
@endsection
