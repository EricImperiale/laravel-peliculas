<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MoviesTrashedController extends Controller
{
    public function index()
    {
        return view('movies.trashed.index', [
            // Traemos solo las películas que hayan sido eliminadas.
            'movies' => Movie::onlyTrashed()->get(),
        ]);
    }

    public function confirmDelete(int $id)
    {
        return view('movies.trashed.confirm-delete', [
            'movie' => Movie::onlyTrashed()->findOrFail($id),
        ]);
    }

    public function processDelete(int $id)
    {
        $movie = Movie::onlyTrashed()->findOrFail($id);

        // forceDelete() es el método para eliminar realmente un registro que usa SoftDeletes.
        // Por supuesto, vamos a necesitar eliminar primero las relaciones con los géneros. De lo contrario,
        // vamos a tener un error de integridad.
        // Para borrar las relaciones con los géneros, podemos simplemente llamar al método DB::detach().
        // Este método puede recibir un id o lista de ids de los registros que queremos "desvincular",
        // o si lo dejamos vacío, elimina todas las relaciones.
        // Recuerden: tenemos que llamar a detach desde la llamada al método de la relación, y no a su
        // propiedad dinámica.
        try {
            DB::transaction(function() use ($movie) {
                $movie->genres()->detach();
//                throw new \Exception(); // Lanzamos una excepción para probar la transacción.
                $movie->forceDelete();
            });
        } catch(\Exception $e) {
            return redirect()
                ->route('movies.trashed.confirmDelete', ['id' => $id])
                ->with('status.message', 'Ocurrió un error al eliminar la película. ...')
                ->with('status.type', 'error');
        }

        $this->deleteCover($movie->cover);

        return redirect()
            ->route('movies.trashed.index')
            ->with('status.message', 'La película <b>' . e($movie->title) . '</b> fue eliminada con éxito.');
    }

    /**
     * Sube la portada (cover), y retorna el nombre generado para el archivo.
     *
     * @param Request $request
     * @return string
     */
    protected function uploadCover(Request $request): string
    {
        // file() retorna una instancia de la clase UploadedFile
        $cover = $request->file('cover');

        // Creamos el nombre del archivo.
        // Por ejemplo, podemos usar la fecha actual, más un "slug" del título, más la extensión
        // del archivo.
        // De paso, ya podemos agregar el nombre a la info que $data debe grabar.
        // Str es la clase de método "helpers" de Laravel para Strings.
        // guessExtension() trata de deducir cuál es la extensión correcta del archivo según el
        // tipo MIME del mismo.
        $coverName = date('YmdHis-') . \Str::slug($request->input('title')) . "." . $cover->guessExtension();

        // Movemos el archivo con la función move de UploadedFile.
//        $cover->move(public_path('imgs'), $coverName);
        // Usando la API de Storage.
        $cover->storeAs('imgs', $coverName);

        return $coverName;
    }

    /**
     * Elimina la imagen de portada (cover) de la película, si existe.
     *
     * @param string|null $cover
     * @return void
     */
    protected function deleteCover(?string $cover): void
    {
        // Versión API Storage.
        if($cover !== null && Storage::has('imgs/' . $cover)) {
            Storage::delete('imgs/' . $cover);
        }
    }
}
