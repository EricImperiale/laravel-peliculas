<?php

namespace App\Providers;

use App\Repositories\Interfaces\MovieRepository;
use App\Repositories\MovieEloquentRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array|string[] Aclaramos qué implementaciones (clases concretas) deben proveerse cuando pidamos qué abstracciones (interfaces).
     */
    public array $bindings = [
        MovieRepository::class => MovieEloquentRepository::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
