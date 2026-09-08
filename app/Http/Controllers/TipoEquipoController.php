<?php

namespace App\Http\Controllers;

use App\Models\TipoEquipo;
use Illuminate\Http\Request;

class TipoEquipoController extends Controller
{
    public function index()
    {
        $tipos = TipoEquipo::orderBy('nombre')->paginate(10);
        return view('admin.tipo_equipos.index', compact('tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:80|unique:tipo_equipos,nombre',
        ]);

        $tipo = new TipoEquipo();
        $tipo->nombre = $request->nombre;
        $tipo->save();

        return redirect()->route('tipo_equipos.index')
            ->with('mensaje', 'Tipo de equipo registrado exitosamente')
            ->with('icono', 'success');
    }

    public function edit($id)
    {
        $tipo = TipoEquipo::find($id);
        return view('admin.tipo_equipos.edit', compact('tipo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:80|unique:tipo_equipos,nombre,' . $id,
        ]);

        $tipo = TipoEquipo::find($id);
        $tipo->nombre = $request->nombre;
        $tipo->save();

        return redirect()->route('tipo_equipos.index')
            ->with('mensaje', 'Tipo de equipo actualizado exitosamente')
            ->with('icono', 'success');
    }

    public function destroy($id)
    {
        $tipo = TipoEquipo::find($id);
        $tipo->delete();

        return redirect()->route('tipo_equipos.index')
            ->with('mensaje', 'Tipo de equipo eliminado exitosamente')
            ->with('icono', 'success');
    }
}
