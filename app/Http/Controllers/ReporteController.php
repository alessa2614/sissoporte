<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\User;
use App\Exports\OrdenesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $desde  = $request->desde ?? now()->startOfMonth()->toDateString();
        $hasta  = $request->hasta ?? now()->toDateString();
        $estado = $request->estado ?? null;

        $query = Orden::with(['cliente', 'tipoEquipo', 'tecnico', 'servicios', 'adicionales'])
            ->whereBetween('created_at', [
                $desde . ' 00:00:00',
                $hasta . ' 23:59:59'
            ]);

        if ($estado) {
            $query->where('estado', $estado);
        }

        $ordenes = $query->orderBy('created_at', 'desc')->get();

        // Estadísticas
        $stats = [
            'total_ordenes'    => $ordenes->count(),
            'entregadas'       => $ordenes->where('estado', 'entregado')->count(),
            'en_proceso'       => $ordenes->whereNotIn('estado', ['entregado'])->count(),
            'total_ingresos'   => $ordenes->where('estado_pago', 'pagado')->sum('total_final'),
            'total_servicios'  => $ordenes->sum(fn($o) => $o->servicios->sum('precio')),
            'total_adicionales' => $ordenes->sum(
                fn($o) => $o->adicionales->where('estado', 'aprobado')->sum('costo')
            ),
        ];

        return view('admin.reportes.index', compact(
            'ordenes',
            'desde',
            'hasta',
            'estado',
            'stats'
        ));
    }

    public function pdf(Request $request)
    {
        // ✅ Validar que venga el filtro desde
        if (!$request->filled('desde') || !$request->filled('hasta')) {
            return redirect()->route('admin.reportes.index')
                ->with('mensaje', 'Selecciona el período antes de generar el PDF')
                ->with('icono', 'info');
        }

        $desde  = $request->desde;
        $hasta  = $request->hasta;
        $estado = $request->estado ?? null;

        $query = Orden::with(['cliente', 'tipoEquipo', 'tecnico', 'servicios', 'adicionales'])
            ->whereBetween('created_at', [
                $desde . ' 00:00:00',
                $hasta . ' 23:59:59'
            ]);

        if ($estado) {
            $query->where('estado', $estado);
        }

        $ordenes = $query->orderBy('created_at', 'desc')->get();

        $totalIngresos = $ordenes
            ->where('estado_pago', 'pagado')
            ->sum('total_final');

        $pdf = Pdf::loadView('admin.reportes.reporte_pdf', compact(
            'ordenes',
            'desde',
            'hasta',
            'estado',
            'totalIngresos'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream('reporte-' . $desde . '-al-' . $hasta . '.pdf');
    }
    public function excel(Request $request)
    {
        // ✅ Validar que venga el filtro desde
        if (!$request->filled('desde') || !$request->filled('hasta')) {
            return redirect()->route('admin.reportes.index')
                ->with('mensaje', 'Selecciona el período antes de generar el Excel')
                ->with('icono', 'info');
        }

        $desde  = $request->desde;
        $hasta  = $request->hasta;
        $estado = $request->estado ?? null;

        return Excel::download(
            new OrdenesExport($desde, $hasta, $estado),
            'reporte-ordenes-' . $desde . '-al-' . $hasta . '.xlsx'
        );
    }
}
