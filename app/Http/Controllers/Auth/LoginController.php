<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Sobreescribimos credentials para inyectar verificación de estado.
     * Laravel llama a attemptLogin() que usa credentials() internamente.
     * Aquí interceptamos ANTES de que el trait autentique.
     */
    protected function attemptLogin(Request $request)
    {
        // Buscar el usuario por email primero
        $user = User::where('email', $request->email)->first();

        // Si existe y está inactivo, rechazar sin intentar autenticar
        if ($user && ! $user->estado) {
            return false;
        }

        // Si está activo, dejar que el trait haga el login normal
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->boolean('remember')
        );
    }

    /**
     * Mensaje de error cuando attemptLogin() devuelve false.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && ! $user->estado) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $this->username() => 'Tu cuenta está desactivada. Contacta al administrador.',
            ]);
        }

        throw \Illuminate\Validation\ValidationException::withMessages([
            $this->username() => trans('auth.failed'),
        ]);
    }
}
