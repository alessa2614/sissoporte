<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Cliente;
use App\Models\Garantia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $resumen = [
            'ordenes_hoy'        => Orden::whereDate('created_at', today())->count(),
            'en_proceso'         => Orden::whereNotIn('estado', ['entregado'])->count(),
            'entregadas_mes'     => Orden::where('estado', 'entregado')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)->count(),
            'ingresos_mes'       => Orden::where('estado_pago', 'pagado')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)->sum('total_final'),
            'garantias_vigentes' => Garantia::where('estado', 'vigente')->count(),
            'total_clientes'     => Cliente::count(),
        ];

        $ingresosMeses = [];
        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $ingresosMeses[] = [
                'mes'   => $fecha->locale('es')->isoFormat('MMM'),
                'total' => Orden::where('estado_pago', 'pagado')
                    ->whereMonth('updated_at', $fecha->month)
                    ->whereYear('updated_at', $fecha->year)->sum('total_final'),
            ];
        }

        $porEstado = $this->getOrdenesEstado('semana');

        $ordenesDias = $this->getDatosPeriodo('semana');

        $topServicios = $this->getTopServicios('semana');

        $ultimasOrdenes = Orden::with(['cliente', 'tipoEquipo', 'tecnico'])
            ->orderBy('created_at', 'desc')->limit(5)->get();

        $tecnicosStats = User::whereHas('roles', fn($q) => $q->where('name', 'TECNICO'))
            ->where('estado', true)
            ->withCount([
                'ordenes as total_entregadas' => fn($q) =>
                $q->where('estado', 'entregado')
                    ->whereMonth('updated_at', now()->month),
            ])
            ->orderByDesc('total_entregadas')->get();

        return view('admin.dashboard', compact(
            'resumen',
            'ingresosMeses',
            'porEstado',
            'ordenesDias',
            'topServicios',
            'ultimasOrdenes',
            'tecnicosStats'
        ));
    }

    // AJAX: actividad por período
    public function actividadPeriodo(Request $request)
    {
        return response()->json($this->getDatosPeriodo($request->periodo ?? 'semana'));
    }

    // AJAX: top servicios por período
    public function topServiciosPeriodo(Request $request)
    {
        return response()->json($this->getTopServicios($request->periodo ?? 'semana'));
    }

    // AJAX: órdenes por estado según período
    public function ordenesEstadoPeriodo(Request $request)
    {
        return response()->json($this->getOrdenesEstado($request->periodo ?? 'semana'));
    }

    // Helper: órdenes por estado
    private function getOrdenesEstado(string $periodo)
    {
        $query = Orden::select('estado', DB::raw('count(*) as total'));

        if ($periodo === 'hoy') {
            $query->whereDate('created_at', today());
        } elseif ($periodo === 'semana') {
            $query->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()]);
        } elseif ($periodo === 'mes') {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }

        // Base con todos los estados en 0 para que el donut siempre tenga estructura completa
        $base = [
            'recibido'              => 0,
            'en_revision'           => 0,
            'esperando_aprobacion'  => 0,
            'en_reparacion'         => 0,
            'listo'                 => 0,
            'entregado'             => 0,
        ];

        $result = $query->groupBy('estado')->get()
            ->mapWithKeys(fn($item) => [$item->estado => (int) $item->total])
            ->toArray();

        return array_merge($base, $result);
    }

    // Helper: datos de actividad
    private function getDatosPeriodo(string $periodo): array
    {
        $datos = [];

        if ($periodo === 'hoy') {
            for ($h = 0; $h < 24; $h++) {
                $datos[] = [
                    'dia'      => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00',
                    'total'    => Orden::whereDate('created_at', today())
                        ->whereRaw('HOUR(created_at) = ?', [$h])->count(),
                    'ingresos' => Orden::where('estado_pago', 'pagado')
                        ->whereDate('updated_at', today())
                        ->whereRaw('HOUR(updated_at) = ?', [$h])->sum('total_final'),
                ];
            }
        } elseif ($periodo === 'semana') {
            for ($i = 6; $i >= 0; $i--) {
                $fecha = now()->subDays($i);
                $datos[] = [
                    'dia'      => $fecha->locale('es')->isoFormat('ddd D'),
                    'total'    => Orden::whereDate('created_at', $fecha->toDateString())->count(),
                    'ingresos' => Orden::where('estado_pago', 'pagado')
                        ->whereDate('updated_at', $fecha->toDateString())->sum('total_final'),
                ];
            }
        } elseif ($periodo === 'mes') {
            $diasEnMes = now()->daysInMonth;
            for ($d = 1; $d <= $diasEnMes; $d++) {
                $fecha = now()->startOfMonth()->addDays($d - 1);
                $datos[] = [
                    'dia'      => $fecha->format('d'),
                    'total'    => Orden::whereDate('created_at', $fecha->toDateString())->count(),
                    'ingresos' => Orden::where('estado_pago', 'pagado')
                        ->whereDate('updated_at', $fecha->toDateString())->sum('total_final'),
                ];
            }
        }

        return $datos;
    }

    // Helper: top servicios
    private function getTopServicios(string $periodo)
    {
        $query = DB::table('orden_servicios')
            ->join('catalogo_servicios', 'orden_servicios.servicio_id', '=', 'catalogo_servicios.id')
            ->select('catalogo_servicios.nombre', DB::raw('count(*) as total'));

        if ($periodo === 'hoy') {
            $query->whereDate('orden_servicios.created_at', today());
        } elseif ($periodo === 'semana') {
            $query->whereBetween('orden_servicios.created_at', [now()->subDays(6)->startOfDay(), now()]);
        } elseif ($periodo === 'mes') {
            $query->whereMonth('orden_servicios.created_at', now()->month)
                ->whereYear('orden_servicios.created_at', now()->year);
        }

        return $query->groupBy('catalogo_servicios.nombre')
            ->orderByDesc('total')->limit(5)->get();
    }
}
