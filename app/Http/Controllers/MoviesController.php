<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Movie;
use App\PaymentProviders\MercadoPagoPayment;
use App\Repositories\Interfaces\MovieRepository;
use App\Searches\MovieSearchParams;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MoviesController extends Controller
{
    protected MovieRepository $repo;

    public function __construct(MovieRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        $builder = $this->repo->withRelations(['country', 'classification', 'genres']);

        $searchParams = new MovieSearchParams(
            title: $request->query('t'),
        );

        if($searchParams->getTitle()) {
            $builder->where('title', 'LIKE', '%' . $searchParams->getTitle() . '%');
        }

        $movies = $builder->paginate(2);

        return view('movies.index', [
            'movies' => $movies,
            'searchParams' => $searchParams,
        ]);
    }

    public function view(int $id)
    {
        $movie = $this->repo->findOrFail($id);

        return view('movies.view', [
            'movie' => $movie,
        ]);
    }

    public function formNew()
    {
        return view('movies.create-form', [
            'countries' => Country::orderBy('name')->get(),
            'classifications' => Classification::orderBy('name')->get(),
            'genres' => Genre::orderBy('name')->get(),
        ]);
    }

    public function processNew(Request $request)
    {
        $this->authorize('create', Movie::class);

        $data = $request->except(['_token']);

        $request->validate(Movie::validationRules(), Movie::validationMessages());

        if($request->hasFile('cover')) {
            $data['cover'] = $this->uploadCover($request);
        }

        try {
            $this->repo->create($data);
        } catch(\Exception $e) {
            Debugbar::log($e);
            return redirect()
                ->route('movies.formNew')
                ->withInput()
                ->with('status.message', 'Ocurrió un error al grabar la información. Por favor, probá de nuevo en un rato. Si el problema persiste, comunicate con nosotros.')
                ->with('status.type', 'danger');
        }

        return redirect()
            ->route('movies.index')
            ->with('status.message', 'La película <b>' . e($data['title']) . '</b> fue publicada con éxito.')
            ->with('status.type', 'success');
    }

    public function formUpdate(int $id)
    {
        return view('movies.update-form', [
            'movie' => Movie::findOrFail($id),
            'countries' => Country::orderBy('name')->get(),
            'classifications' => Classification::orderBy('name')->get(),
            'genres' => Genre::orderBy('name')->get(),
        ]);
    }

    public function processUpdate(int $id, Request $request)
    {
        $movie = Movie::findOrFail($id);

        $this->authorize('update', $movie);

        $data = $request->except(['_token']);

        if($request->hasFile('cover')) {
            $data['cover'] = $this->uploadCover($request);

            $oldCover = $movie->cover;
        }

        try {
            DB::transaction(function() use ($movie, $data) {
                $movie->update($data);

                $movie->genres()->sync($data['genre_id'] ?? []);
            });
        } catch(\Exception $e) {
            return redirect()
                ->route('movies.formUpdate', ['id' => $movie->movie_id])
                ->withInput()
                ->with('status.message', 'Ocurrió un error al actualizar la información. Por favor, probá de nuevo en un rato. Si el problema persiste, comunicate con nosotros.')
                ->with('status.type', 'error');
        }

        $this->deleteCover($oldCover ?? null);

        return redirect()
            ->route('movies.index')
            ->with('status.message', 'La película <b>' . e($movie->title) . '</b> fue editada con éxito.')
            ->with('status.type', 'success');
    }

    public function confirmDelete(int $id)
    {
        return view('movies.confirm-delete', [
            'movie' => Movie::findOrFail($id),
        ]);
    }

    public function processDelete(int $id)
    {
        $movie = Movie::findOrFail($id);

        $this->authorize('delete', $movie);

        $movie->delete();

        $this->deleteCover($movie->cover);

        return redirect()
            ->route('movies.index')
            ->with('status.message', 'La película <b>' . e($movie->title) . '</b> fue eliminada con éxito.')
            ->with('status.type', 'success');
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
