<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::paginate(10);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|string|exists:roles,name',
        ]);

        $usuario           = new User();
        $usuario->name     = $request->name;
        $usuario->email    = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->estado   = $request->has('estado') ? true : false;
        $usuario->save();

        $usuario->assignRole($request->role);

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario creado exitosamente')
            ->with('icono', 'success');
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles   = Role::all();
        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|string|exists:roles,name',
        ]);

        $usuario         = User::findOrFail($id);
        $usuario->name   = $request->name;
        $usuario->email  = $request->email;
        $usuario->estado = $request->has('estado') ? true : false;

        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->password);
        }

        $usuario->save();
        $usuario->syncRoles($request->role);

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario actualizado exitosamente')
            ->with('icono', 'success');
    }

    // ── En vez de borrar, solo desactiva ─────────────────────────
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        if ($usuario->id === Auth::id()) {
            return redirect()->back()
                ->with('mensaje', 'No puedes desactivar tu propia cuenta')
                ->with('icono', 'error');
        }

        $usuario->estado = false;
        $usuario->save();

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', "Usuario {$usuario->name} desactivado. Sus datos se conservan.")
            ->with('icono', 'success');
    }

    // ── Toggle activar / desactivar ───────────────────────────────
    public function toggle($id)
    {
        $usuario = User::findOrFail($id);

        if ($usuario->id === Auth::id()) {
            return redirect()->back()
                ->with('mensaje', 'No puedes modificar el estado de tu propia cuenta')
                ->with('icono', 'error');
        }

        $usuario->estado = !$usuario->estado;
        $usuario->save();

        $texto = $usuario->estado ? 'activado' : 'desactivado';

        return redirect()->back()
            ->with('mensaje', "Usuario {$usuario->name} {$texto} correctamente")
            ->with('icono', 'success');
    }
}
