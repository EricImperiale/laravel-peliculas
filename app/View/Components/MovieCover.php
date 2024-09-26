<?php

namespace App\View\Components;

use App\Models\Movie;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MovieCover extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Movie $movie,
        public ?string $alt = null,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.movie-cover');
    }
}
