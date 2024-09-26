<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AuthRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function formLogin()
    {
        return view('auth.form-login');
    }

    public function processLogin(AuthRequest $request)
    {
        // TODO: Validar...
        // Vamos a autenticar al usuario usando la API de Authentication de Laravel.
        // Para usarla, vamos a definir un array de "credenciales".
        // Este array debe contener, al menos, 2 claves:
        // 1. Una clave "password" (DEBE llamarse así) que contenga el valor del password.
        // 2. Las claves con las que queremos buscar al usuario en el almacenamiento. Por ejemplol, un
        //  campo "email".
        // La forma en que la autenticación de Laravel funciona es la siguiente:
        // Usamos el método de Auth::attempt que recibe las credenciales y trata de autenticar al usuario.
        // Esto lo hace buscando a los usuarios en el sistema de almacenamiento que usemos (ej: vía
        // Eloquent), que a su vez lo hace usando todos los campos que *no* se llamen "password" en las
        // credenciales.
        $credentials = $request->only(['email', 'password']);

        // Para acceder a la API de Authentication podemos usar la fachada "Auth" o la función helper
        // "auth()".
        if(!auth()->attempt($credentials)) {
            // El login falló, lo redireccionamos de nuevo al form de iniciar sesión.
            return redirect()
                ->route('auth.formLogin')
                ->with('status.message', 'Las credenciales ingresadas no coinciden con nuestros registros.')
                ->with('status.type', 'danger')
                // "withInput()" flashea los datos del formulario a la sesión para poder ser usados con el
                // helper "old()".
                ->withInput();
        }

        // ¡El usuario está autenticado! \o/
        // Auth::attempt() automáticamente autentica al usuario si las credenciales son correctas.
        // Lo único que nos quedaría por hacer, es seguir la recomendación de Laravel de regenerar la
        // sesión para evitar vulnerabilidades de "Session Fixation".
        $request->session()->regenerate();

        return redirect()
            ->route('movies.index')
            ->with('status.type', 'success')
            ->with('status.message', 'Iniciaste sesión con éxito. ¡Hola de nuevo!');
    }

    public function processLogout(Request $request)
    {
        auth()->logout();

        // Nuevamente, seguimos las recomendaciones de Laravel de seguridad y hacemos 2 cosas:
        // 1. Invalidar el id de sesión que se usó previamente.
        // 2. Regenerar el token de CSRF.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('auth.formLogin')
            ->with('status.type', 'success')
            ->with('status.message', 'Cerrarse tu sesión con éxito. ¡Volvé pronto!');
    }
}
