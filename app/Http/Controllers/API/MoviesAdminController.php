<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MoviesAdminController extends Controller
{
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
        $movie = Movie::create($request->only(['country_id', 'classification_id', 'title', 'release_date', 'price' , 'synopsis', 'cover', 'cover_description']));

        return response()->json([
            'status' => 0,
            'data' => $movie
        ]);
    }
}
