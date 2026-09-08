<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use Illuminate\Http\Request;

class ConsultaController extends Controller
{
    // GET /consulta
    public function index()
    {
        return view('web.consulta');
    }

    // POST /consulta
    public function buscar(Request $request)
    {
        $request->validate([
            'codigo'  => 'required|string|max:20',
            'celular' => 'required|string|max:15',
        ], [
            'codigo.required'  => 'Ingresa tu código de orden.',
            'celular.required' => 'Ingresa tu número de celular.',
        ]);

        $orden = Orden::with(['cliente', 'tipoEquipo', 'tecnico', 'historial', 'adicionales', 'garantia'])
            ->where('codigo', strtoupper(trim($request->codigo)))
            ->whereHas(
                'cliente',
                fn($q) =>
                $q->where('celular', trim($request->celular))
            )
            ->first();

        $vista = $request->input('origen') === 'home' ? 'web.home' : 'web.consulta';

        if (!$orden) {
            return view($vista, [
                'error'   => true,
                'codigo'  => $request->codigo,
                'celular' => $request->celular,
            ]);
        }

        return view($vista, [
            'orden'   => $orden,
            'codigo'  => $request->codigo,
            'celular' => $request->celular,
        ]);
    }
}
