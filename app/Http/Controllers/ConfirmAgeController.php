<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfirmAgeController extends Controller
{
    public function formConfirmation(int $id)
    {
        return view('movies.confirm-age', [
            'id' => $id,
        ]);
    }

    public function processConfirmation(Request $request, int $id)
    {
        // put() es el método para guardar un valor.
        $request->session()->put('ageVerified', true);

        // Lo redireccionamos a la película que el usuario quería ver.
        return redirect()
            ->route('movies.view', ['id' => $id]);
    }
}
