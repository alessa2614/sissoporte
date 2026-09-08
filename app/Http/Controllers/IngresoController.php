<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngresoController extends Controller
{
    public function index(Request $request)
    {
        $desde      = $request->desde ?? now()->startOfMonth()->toDateString();
        $hasta      = $request->hasta ?? now()->toDateString();
        $tecnico_id = $request->tecnico_id ?? null;

        // ── Condiciones base compartidas ─────────────────────────────
        $condiciones = function ($q) use ($desde, $hasta, $tecnico_id) {
            $q->where('estado', 'entregado')
                ->where('estado_pago', 'pagado')
                ->whereBetween('updated_at', [
                    $desde . ' 00:00:00',
                    $hasta . ' 23:59:59',
                ]);
            if ($tecnico_id) {
                $q->where('tecnico_id', $tecnico_id);
            }
        };

        // ── Tabla paginada (con relaciones para mostrar) ─────────────
        $ordenes = Orden::with([
            'cliente',
            'tipoEquipo',
            'tecnico',
            'servicios',
            'adicionales'
        ])->tap($condiciones)
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
            ->withQueryString(); // mantiene filtros en los links de paginación

        // ── Totales del período COMPLETO (query separada, sin paginar) ─
        $idsDelPeriodo = Orden::tap($condiciones)->pluck('id');

        $totalPeriodo = Orden::tap($condiciones)->sum('total_final');

        $totalServicios = DB::table('orden_servicios')
            ->whereIn('orden_id', $idsDelPeriodo)
            ->sum('precio');

        $totalAdicionales = DB::table('adicionales')
            ->whereIn('orden_id', $idsDelPeriodo)
            ->where('estado', 'aprobado')
            ->sum('costo');

        // ── Órdenes del período para stats extra ────────────────────
        $totalOrdenes   = $idsDelPeriodo->count();
        $ticketPromedio = $totalOrdenes > 0
            ? round($totalPeriodo / $totalOrdenes, 2)
            : 0;

        // ── Técnico más productivo del período ──────────────────────
        $tecnicoTop = Orden::tap($condiciones)
            ->select('tecnico_id', DB::raw('SUM(total_final) as total'), DB::raw('COUNT(*) as cantidad'))
            ->whereNotNull('tecnico_id')
            ->groupBy('tecnico_id')
            ->orderByDesc('total')
            ->with('tecnico:id,name')
            ->first();

        // ── Técnicos para el filtro ──────────────────────────────────
        $tecnicos = User::whereHas('roles', fn($q) => $q->where('name', 'TECNICO'))
            ->where('estado', true)
            ->get();

        return view('admin.ingresos.index', compact(
            'ordenes',
            'desde',
            'hasta',
            'tecnico_id',
            'tecnicos',
            'totalPeriodo',
            'totalServicios',
            'totalAdicionales',
            'totalOrdenes',
            'ticketPromedio',
            'tecnicoTop'
        ));
    }
}
