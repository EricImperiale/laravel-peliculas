<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MoviesAdminController extends Controller
{
    // TODO: Valdaciones, Respetar Polcies
    public function index()
    {
        return response()->json([
            'status' => 0,
            'data' => Movie::all(),
        ]);
    }

    public function view(int $id)
    {
        return response()->json([
            'status' => 0,
            'data' => Movie::findOrFail($id),
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Movie::class);

        $movie = Movie::create($request->only(['country_id', 'classification_id', 'title', 'release_date', 'price' , 'synopsis', 'cover', 'cover_description']));

        return response()->json([
            'status' => 0,
            'data' => $movie
        ]);
    }

    public function update(int $id, Request $request)
    {
        $movie = Movie::findOrFail($id);

        $this->authorize('update', $movie);

        $movie->update($request->only('title'));

        return response()->json([
            'status' => 0,
            'data' => $movie,
        ]);
    }

    public function delete(int $id)
    {
        $movie = Movie::findOrFail($id);

        $this->authorize('delete', $movie);

        $movie->delete();

        return response()->json([
            'status' => 0,
        ]);
    }
}
