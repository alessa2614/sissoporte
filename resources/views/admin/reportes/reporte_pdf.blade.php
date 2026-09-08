<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10px; color: #333; }

        .header { text-align: center; margin-bottom: 15px; }
        .header h1 { font-size: 18px; color: #435ebe; }
        .header p  { font-size: 11px; color: #666; }

        .stats {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .stat-box {
            display: table-cell;
            text-align: center;
            border: 1px solid #ddd;
            padding: 8px;
            width: 25%;
        }
        .stat-box .valor { font-size: 16px; font-weight: bold; color: #435ebe; }
        .stat-box .label { font-size: 9px; color: #666; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead tr { background: #435ebe; color: white; }
        th { padding: 6px 4px; text-align: left; font-size: 9px; }
        td { padding: 5px 4px; border-bottom: 1px solid #eee; font-size: 9px; }
        tr:nth-child(even) { background: #f8f9fa; }

        .total-row { background: #e8f5e9 !important; font-weight: bold; }
        .footer { margin-top: 15px; text-align: center; font-size: 9px; color: #999; }
    </style>
</head>
<body>

    {{-- ENCABEZADO --}}
    <div class="header">
        <h1>NEXOS TIENDAS — Reporte de Órdenes</h1>
        <p>
            Período: {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }}
            al {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}
            @if($estado) | Estado: {{ strtoupper(str_replace('_',' ',$estado)) }} @endif
        </p>
        <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- ESTADÍSTICAS --}}
    <div class="stats">
        <div class="stat-box">
            <div class="valor">{{ $ordenes->count() }}</div>
            <div class="label">Total Órdenes</div>
        </div>
        <div class="stat-box">
            <div class="valor">{{ $ordenes->where('estado','entregado')->count() }}</div>
            <div class="label">Entregadas</div>
        </div>
        <div class="stat-box">
            <div class="valor">{{ $ordenes->whereNotIn('estado',['entregado'])->count() }}</div>
            <div class="label">En Proceso</div>
        </div>
        <div class="stat-box">
            <div class="valor" style="color:#28a745;">
                S/. {{ number_format($totalIngresos, 2) }}
            </div>
            <div class="label">Total Ingresos</div>
        </div>
    </div>

    {{-- TABLA --}}
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Cliente</th>
                <th>Clelular</th>
                <th>Equipo</th>
                <th>Marca/Modelo</th>
                <th>Problema</th>
                <th>Técnico</th>
                <th>Estado</th>
                <th>Servicios</th>
                <th>Adicionales</th>
                <th>Total</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ordenes as $orden)
                <tr>
                    <td>{{ $orden->codigo }}</td>
                    <td>{{ $orden->cliente->nombre }}</td>
                    <td>{{ $orden->cliente->celular }}</td>
                    <td>{{ $orden->tipoEquipo->nombre }}</td>
                    <td>{{ $orden->marca }} {{ $orden->modelo }}</td>
                    <td>{{ $orden->descripcion }}</td>
                    <td>{{ $orden->tecnico->name ?? '—' }}</td>
                    <td>{{ strtoupper(str_replace('_',' ',$orden->estado)) }}</td>
                    <td>S/. {{ number_format($orden->servicios->sum('precio'), 2) }}</td>
                    <td>S/. {{ number_format($orden->adicionales->where('estado','aprobado')->sum('costo'), 2) }}</td>
                    <td>S/. {{ number_format($orden->total_final ?? 0, 2) }}</td>
                    <td>{{ $orden->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center; color:#999;">
                        No hay registros en este período.
                    </td>
                </tr>
            @endforelse

            {{-- FILA TOTAL --}}
            @if($ordenes->count() > 0)
                <tr class="total-row">
                    <td colspan="8" style="text-align:right;">TOTAL INGRESOS:</td>
                    <td>S/. {{ number_format($ordenes->sum(fn($o) => $o->servicios->sum('precio')), 2) }}</td>
                    <td>S/. {{ number_format($ordenes->sum(fn($o) => $o->adicionales->where('estado','aprobado')->sum('costo')), 2) }}</td>
                    <td>S/. {{ number_format($totalIngresos, 2) }}</td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        NEXOS TIENDAS — Sistema de Soporte Técnico — Juliaca, Puno
    </div>

</body>
</html>