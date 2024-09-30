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
        if($request->session()->get('ageVerified', false)) return $next($request);

        $id = $request->route()->parameter('id');
        $movie = Movie::findOrFail($id);

        if($movie->classification_id === 4) {
            return redirect()
                ->route('movies.confirm-age.formConfirmation', ['id' => $id]);
        }

        return $next($request);
    }
}
