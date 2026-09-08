<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $clientes = Cliente::orderBy('nombre')
            ->when($q, function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                    ->orWhere('celular', 'like', "%{$q}%");
            })
            ->paginate(10)
            ->withQueryString();

        // Respuesta AJAX
        if ($request->ajax()) {
            return response()->json([
                'filas'      => view('admin.clientes._tabla_filas', compact('clientes'))->render(),
                'paginacion' => view('admin.clientes._paginacion', compact('clientes'))->render(),
                'total'      => $clientes->total(),
            ]);
        }

        return view('admin.clientes.index', compact('clientes', 'q'));
    }

    public function create()
    {
        return view('admin.clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'  => 'required|string|max:100',
            'celular' => 'required|string|max:15|unique:clientes,celular',
            'correo'  => 'nullable|email|max:150|unique:clientes,correo',
        ], [
            'celular.unique' => 'Ya existe un cliente registrado con ese número de celular.',
            'correo.unique'  => 'Ya existe un cliente registrado con ese correo electrónico.',
        ]);

        $cliente          = new Cliente();
        $cliente->nombre  = mb_convert_case(trim($request->nombre), MB_CASE_TITLE, 'UTF-8');
        $cliente->celular = $request->celular;
        $cliente->correo  = $request->filled('correo') ? $request->correo : null;
        $cliente->save();

        return redirect()->route('admin.clientes.index')
            ->with('mensaje', 'Cliente registrado exitosamente')
            ->with('icono', 'success');
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('admin.clientes.show', compact('cliente'));
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'  => 'required|string|max:100',
            'celular' => "required|string|max:15|unique:clientes,celular,{$id}",
            'correo'  => "nullable|email|max:150|unique:clientes,correo,{$id}",
        ], [
            'celular.unique' => 'Ya existe un cliente registrado con ese número de celular.',
            'correo.unique'  => 'Ya existe un cliente registrado con ese correo electrónico.',
        ]);

        $cliente          = Cliente::findOrFail($id);
        $cliente->nombre  = mb_convert_case(trim($request->nombre), MB_CASE_TITLE, 'UTF-8');
        $cliente->celular = $request->celular;
        $cliente->correo  = $request->filled('correo') ? $request->correo : null;
        $cliente->save();

        return redirect()->route('admin.clientes.index')
            ->with('mensaje', 'Cliente actualizado exitosamente')
            ->with('icono', 'success');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);

        if ($cliente->ordenes()->exists()) {
            return redirect()->route('admin.clientes.index')
                ->with('mensaje', 'No se puede eliminar el cliente porque tiene órdenes asociadas.')
                ->with('icono', 'error');
        }

        $cliente->delete();

        return redirect()->route('admin.clientes.index')
            ->with('mensaje', 'Cliente eliminado exitosamente')
            ->with('icono', 'success');
    }

    public function historial($id)
    {
        $cliente = \App\Models\Cliente::findOrFail($id);

        $ordenes = \App\Models\Orden::with([
            'tipoEquipo',
            'tecnico',
            'servicios.servicio',
            'adicionales',
            'garantia'
        ])
            ->where('cliente_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_ordenes' => $ordenes->count(),
            'entregadas'    => $ordenes->where('estado', 'entregado')->count(),
            'en_proceso'    => $ordenes->whereNotIn('estado', ['entregado'])->count(),
            'total_gastado' => $ordenes->where('estado_pago', 'pagado')->sum('total_final'),
            'con_garantia'  => $ordenes->filter(
                fn($o) => $o->garantia && $o->garantia->estado === 'vigente'
            )->count(),
        ];

        return view('admin.clientes.historial', compact('cliente', 'ordenes', 'stats'));
    }
}
