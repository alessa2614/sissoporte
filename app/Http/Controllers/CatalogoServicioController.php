<?php

namespace App\Http\Controllers;

use App\Models\CatalogoServicio;
use Illuminate\Http\Request;

class CatalogoServicioController extends Controller
{
    public function index()
    {
        $servicios = CatalogoServicio::orderBy('nombre')->paginate(10);
        return view('admin.catalogo_servicios.index', compact('servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100|unique:catalogo_servicios,nombre',
            'precio_base' => 'required|numeric|min:0',
        ]);

        $servicio = new CatalogoServicio();
        $servicio->nombre      = $request->nombre;
        $servicio->precio_base = $request->precio_base;
        $servicio->save();

        return redirect()->route('catalogo_servicios.index')
            ->with('mensaje', 'Servicio registrado exitosamente')
            ->with('icono', 'success');
    }

    public function edit($id)
    {
        $servicio = CatalogoServicio::find($id);
        return view('admin.catalogo_servicios.edit', compact('servicio'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100|unique:catalogo_servicios,nombre,' . $id,
            'precio_base' => 'required|numeric|min:0',
        ]);

        $servicio = CatalogoServicio::find($id);
        $servicio->nombre      = $request->nombre;
        $servicio->precio_base = $request->precio_base;
        $servicio->save();

        return redirect()->route('catalogo_servicios.index')
            ->with('mensaje', 'Servicio actualizado exitosamente')
            ->with('icono', 'success');
    }

    public function destroy($id)
    {
        $servicio = CatalogoServicio::find($id);
        $servicio->delete();

        return redirect()->route('catalogo_servicios.index')
            ->with('mensaje', 'Servicio eliminado exitosamente')
            ->with('icono', 'success');
    }
}
