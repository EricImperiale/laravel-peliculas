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
        $request->session()->put('ageVerified', true);

        return redirect()
            ->route('movies.view', ['id' => $id]);
    }
}
