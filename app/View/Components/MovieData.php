<?php

namespace App\View\Components;

use App\Models\Movie;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MovieData extends Component
{
//    public Movie $movie;
//
//    /**
//     * Create a new component instance.
//     */
//    public function __construct(Movie $movie)
//    {
//        $this->movie = $movie;
//    }

    // # Constructor Property Promotion
    // https://www.php.net/manual/en/language.oop5.decon.php#language.oop5.decon.constructor.promotion
    // Con frecuencia ocurre que en una clase tenemos que definir propiedades que deben ser provistas al
    // constructor para que las cargue. Como hicimos en el código de arriba.
    // Esto requiere un poco de "boilerplate" para funcionar:
    // 1. Declarar la propiedad.
    // 2. Definir como parámetro del constructor una variable para recibir el valor.
    // 3. Asignar el valor de ese argumento a la propiedad.
    // Para simplificarnos la vida, en php 8+ se agregó la posibilidad de hacer todo esto en un solo paso,
    // como vamos a ver a continuación.
    // En esencia, solo tenemos que agregar en el parámetro del constructor el "modificador de visibilidad",
    // y eso php lo traduce a que debe "promover" ese argumento a una propiedad de la clase.
    public function __construct(
        public Movie $movie
    )
    {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.movie-data');
    }
}
