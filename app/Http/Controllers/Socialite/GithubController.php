<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GithubController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function callback()
    {
        try {
            $githubUser = Socialite::driver('github')->stateless()->user();

            $user = User::where('email', $githubUser->email)->first();

            if ($user) {
                $user->update([
                    'nickname' => $githubUser->nickname,
                    'provider_id' => $githubUser->id,
                    'provider_token' => $githubUser->token,
                    'provider_refresh_token' => $githubUser->refreshToken ?? null,
                ]);
            } else {
                $user = User::create([
                    'email' => $githubUser->email,
                    'nickname' => $githubUser->nickname,
                    'provider_id' => $githubUser->id,
                    'provider_token' => $githubUser->token,
                    'provider_refresh_token' => $githubUser->refreshToken ?? null,
                ]);
            }

            Auth::login($user);

            return redirect()
                ->route('movies.index')
                ->with('status.type', 'success')
                ->with('status.message', 'Iniciaste sesión con éxito. ¡Hola de nuevo!');
        } catch (\Throwable $err) {
            /* return redirect()
                ->route('movies.index')
                ->with('status.type', 'danger')
                ->with('status.message', 'Ocurrío un error al autenticarte. Intentá más tarde.' . $err->getMessage()); */

            throw $err;
        }
    }
}
