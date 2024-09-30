<?php

namespace App\Http\Controllers;

use App\Mail\MovieReserved;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MoviesReservationController extends Controller
{
    public function processReservation(Request $request, int $id)
    {
        $movie = Movie::findOrFail($id);

        Mail::to(auth()->user()->email)
            ->send(new MovieReserved($movie));

        return redirect()
            ->route('movies.index')
            ->with('status.message', 'La película <b>' . $movie->title . '</b> fue reservada con éxito.')
            ->with('status.type', 'success');
    }
}
