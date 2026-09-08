<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::paginate(5);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $role = new Role();
        $role->name = strtoupper($request->name);
        $role->save();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol creado exitosamente')
            ->with('icono', 'success');
    }

    public function show($id)
    {
        $rol = Role::find($id);
        return view('admin.roles.show', compact('rol'));
    }

    public function edit($id)
    {
        $rol = Role::find($id);
        return view('admin.roles.edit', compact('rol'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        $rol = Role::find($id);
        $rol->name = strtoupper($request->name);
        $rol->save();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol actualizado exitosamente')
            ->with('icono', 'success');
    }

    public function destroy($id)
    {
        $rol = Role::find($id);
        if ($rol->users()->exists()) {
            return redirect()->route('admin.roles.index')
                ->with('mensaje', 'No se puede eliminar el rol porque tiene usuarios asociados.')
                ->with('icono', 'error');
        }
        $rol->delete();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol eliminado exitosamente')
            ->with('icono', 'success');
    }

    // ── PERMISOS ──────────────────────────────────────
    public function permisos($id)
    {
        $role     = Role::find($id);
        $permisos = Permission::all();
        return view('admin.roles.permisos', compact('role', 'permisos'));
    }

    public function permisosUpdate(Request $request, $id)
    {
        $role = Role::find($id);

        // Sincroniza los permisos seleccionados
        // Si no se seleccionó ninguno, quita todos
        $role->syncPermissions($request->permisos ?? []);

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Permisos actualizados correctamente')
            ->with('icono', 'success');
    }
}
