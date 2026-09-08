<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    public function index()
    {
        /** @var User $usuario */
        $usuario = Auth::user();

        if (!$usuario) {
            abort(403);
        }

        return view('admin.perfil.index', compact('usuario'));
    }

    public function actualizar(Request $request)
    {
        /** @var User $usuario */
        $usuario = Auth::user();

        if (!$usuario) {
            abort(403);
        }

        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email,' . $usuario->id,
        ]);

        $usuario->name  = $request->name;
        $usuario->email = $request->email;
        $usuario->save();

        return redirect()->route('admin.perfil.index')
            ->with('mensaje', 'Perfil actualizado correctamente')
            ->with('icono', 'success');
    }

    public function cambiarPassword(Request $request)
    {
        /** @var User $usuario */
        $usuario = Auth::user();

        if (!$usuario) {
            abort(403);
        }

        $request->validate([
            'password_actual' => 'required',
            'password_nuevo'  => 'required|min:8|confirmed',
        ], [
            'password_nuevo.confirmed' => 'Las contraseñas nuevas no coinciden',
            'password_nuevo.min'       => 'La contraseña debe tener al menos 8 caracteres',
        ]);

        // Verificar contraseña actual
        if (!Hash::check($request->password_actual, $usuario->password)) {
            return back()
                ->with('mensaje', 'La contraseña actual es incorrecta')
                ->with('icono', 'error');
        }

        $usuario->password = Hash::make($request->password_nuevo);
        $usuario->save();

        return redirect()->route('admin.perfil.index')
            ->with('mensaje', 'Contraseña actualizada correctamente')
            ->with('icono', 'success');
    }
}
