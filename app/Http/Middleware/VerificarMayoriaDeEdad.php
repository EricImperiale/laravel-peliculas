<?php

namespace App\Http\Middleware;

use App\Models\Movie;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/*
 * Las clases para middlewares de Laravel solo requieren tener un método "handle".
 * Ese método recibe la petición y un closure.
 * El closure debería ejecutarse con la petición si queremos permitir que la misma siga su curso.
 */
class VerificarMayoriaDeEdad
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Nuestro objetivo con este middleware es verificar si la película requiere que el usuario
        // confirme que tiene más de 18 años.
        // Si lo requiere, vamos a pedirle que confirme su edad antes de continuar.
        // Una vez que lo confirme, vamos a guardarlo en una variable de sesión, para no tener que volver
        // a preguntarle por un tiempito.

        // Si el usuario ya verificó su edad, entonces lo dejamos pasar directamente.
        if($request->session()->get('ageVerified', false)) return $next($request);

        // Obtenemos el id de la película. Este id es un parámetro de la ruta, así que podemos pedírselo.
        $id = $request->route()->parameter('id');
        $movie = Movie::findOrFail($id);

        // Preguntamos si la película requiere esta verificación, y si aún no se verificó.
        if($movie->classification_id === 4) {
            // La película es para mayores de 18.
            return redirect()
                ->route('movies.confirm-age.formConfirmation', ['id' => $id]);
        }

        return $next($request);
    }
}
