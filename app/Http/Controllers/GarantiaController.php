<?php

namespace App\Http\Controllers;

use App\Models\Garantia;
use Illuminate\Http\Request;

class GarantiaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q', '');

        $garantias = \App\Models\Garantia::with(['orden.cliente', 'orden.tipoEquipo'])
            ->when($q, function ($query) use ($q) {
                $query->whereHas('orden.cliente', function ($q2) use ($q) {
                    $q2->where('nombre', 'like', "%{$q}%")
                        ->orWhere('celular', 'like', "%{$q}%");
                })->orWhereHas('orden', function ($q2) use ($q) {
                    $q2->where('codigo', 'like', "%{$q}%")
                        ->orWhere('marca',  'like', "%{$q}%");
                })->orWhereHas('orden.tipoEquipo', function ($q2) use ($q) {
                    $q2->where('nombre', 'like', "%{$q}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Respuesta AJAX para el buscador
        if ($request->ajax()) {
            return response()->json([
                'filas'      => view('admin.garantias._tabla_filas',  compact('garantias'))->render(),
                'paginacion' => view('admin.garantias._paginacion',   compact('garantias'))->render(),
                'total'      => $garantias->total(),
            ]);
        }

        return view('admin.garantias.index', compact('garantias', 'q'));
    }

    public function show($id)
    {
        $garantia = Garantia::with([
            'orden.cliente',
            'orden.tipoEquipo',
            'orden.tecnico',
            'orden.servicios.servicio',
            'orden.adicionales',
        ])->findOrFail($id);

        return view('admin.garantias.show', compact('garantia'));
    }

    public function marcarUsada(Request $request, $id)
    {
        $garantia              = Garantia::findOrFail($id);
        $garantia->estado      = 'usada';
        $garantia->observacion = $request->observacion ?? $garantia->observacion;
        $garantia->save();

        return redirect()->route('admin.garantias.index')
            ->with('mensaje', 'Garantía marcada como usada')
            ->with('icono', 'warning');
    }
}
