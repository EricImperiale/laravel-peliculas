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

        try {
            DB::transaction(function() use ($movie) {
                $movie->genres()->detach();
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
        $cover = $request->file('cover');

        $coverName = date('YmdHis-') . \Str::slug($request->input('title')) . "." . $cover->guessExtension();

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
        if($cover !== null && Storage::has('imgs/' . $cover)) {
            Storage::delete('imgs/' . $cover);
        }
    }
}
