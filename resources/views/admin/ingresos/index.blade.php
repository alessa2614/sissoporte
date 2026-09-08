@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Ingresos</h3>
    </div>

    {{-- FILTROS --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.ingresos.index') }}" method="GET">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label>Desde</label>
                        <input type="date" name="desde" class="form-control" value="{{ $desde }}">
                    </div>
                    <div class="col-md-3">
                        <label>Hasta</label>
                        <input type="date" name="hasta" class="form-control" value="{{ $hasta }}">
                    </div>
                    <div class="col-md-3">
                        <label>Técnico</label>
                        <select name="tecnico_id" class="form-control">
                            <option value="">-- Todos --</option>
                            @foreach ($tecnicos as $t)
                                <option value="{{ $t->id }}" {{ $tecnico_id == $t->id ? 'selected' : '' }}>
                                    {{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('admin.ingresos.index') }}" class="btn btn-outline-secondary w-100"
                            title="Limpiar filtros">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TARJETAS RESUMEN --}}
    <div class="row mb-4 g-3">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #198754 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total del período</p>
                            <h3 class="fw-bold text-success mb-0">
                                S/. {{ number_format($totalPeriodo, 2) }}
                            </h3>
                            <small class="text-muted">{{ $totalOrdenes }} orden{{ $totalOrdenes !== 1 ? 'es' : '' }}</small>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;background:rgba(25,135,84,.12);">
                            <i class="bi bi-cash-coin text-success fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #0d6efd !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Por servicios</p>
                            <h3 class="fw-bold text-primary mb-0">
                                S/. {{ number_format($totalServicios, 2) }}
                            </h3>
                            <small class="text-muted">
                                {{ $totalPeriodo > 0 ? number_format(($totalServicios / $totalPeriodo) * 100, 0) : 0 }}%
                                del total
                            </small>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;background:rgba(13,110,253,.12);">
                            <i class="bi bi-tools text-primary fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #ffc107 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Por adicionales</p>
                            <h3 class="fw-bold text-warning mb-0">
                                S/. {{ number_format($totalAdicionales, 2) }}
                            </h3>
                            <small class="text-muted">
                                {{ $totalPeriodo > 0 ? number_format(($totalAdicionales / $totalPeriodo) * 100, 0) : 0 }}%
                                del total
                            </small>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;background:rgba(255,193,7,.12);">
                            <i class="bi bi-plus-circle text-warning fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #6f42c1 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Ticket promedio</p>
                            <h3 class="fw-bold mb-0" style="color:#6f42c1;">
                                S/. {{ number_format($ticketPromedio, 2) }}
                            </h3>
                            @if ($tecnicoTop)
                                <small class="text-muted">
                                    Top: {{ $tecnicoTop->tecnico->name ?? '—' }}
                                </small>
                            @else
                                <small class="text-muted">por orden</small>
                            @endif
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;background:rgba(111,66,193,.12);">
                            <i class="bi bi-graph-up fs-5" style="color:#6f42c1;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- TABLA --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                Órdenes pagadas
                <small class="text-muted fw-normal" style="font-size:13px;">
                    {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }}
                    al
                    {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}
                </small>
            </h4>
            @if ($ordenes->total() > 0)
                <span class="badge bg-success">{{ $ordenes->total() }}
                    orden{{ $ordenes->total() !== 1 ? 'es' : '' }}</span>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Cliente</th>
                            <th>Equipo</th>
                            <th>Técnico</th>
                            <th class="text-end">Servicios</th>
                            <th class="text-end">Adicionales</th>
                            <th class="text-end">Total</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $subtotalPagina = 0; @endphp

                        @forelse($ordenes as $orden)
                            @php
                                $subtotalPagina += $orden->total_final ?? 0;
                                $adicAprobados = $orden->adicionales->where('estado', 'aprobado');
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('admin.ordenes.show', $orden->id) }}" class="fw-bold">
                                        {{ $orden->codigo }}
                                    </a>
                                </td>
                                <td>{{ $orden->cliente->nombre }}</td>
                                <td>
                                    {{ $orden->tipoEquipo->nombre }}
                                    @if ($orden->marca)
                                        <br><small class="text-muted">{{ $orden->marca }}</small>
                                    @endif
                                </td>
                                <td>{{ $orden->tecnico->name ?? '—' }}</td>
                                <td class="text-end">
                                    S/. {{ number_format($orden->servicios->sum('precio'), 2) }}
                                    <br><small class="text-muted">{{ $orden->servicios->count() }} serv.</small>
                                </td>
                                <td class="text-end">
                                    @if ($adicAprobados->count() > 0)
                                        S/. {{ number_format($adicAprobados->sum('costo'), 2) }}
                                        <br><small class="text-muted">{{ $adicAprobados->count() }} adic.</small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <strong class="text-success">
                                        S/. {{ number_format($orden->total_final ?? 0, 2) }}
                                    </strong>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($orden->updated_at)->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No hay ingresos en este período.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if ($ordenes->count() > 0)
                        <tfoot>
                            {{-- Subtotal de la página --}}
                            <tr class="table-light">
                                <td colspan="6" class="text-end text-muted small">
                                    Subtotal esta página ({{ $ordenes->count() }} órdenes):
                                </td>
                                <td class="text-end">
                                    <strong>S/. {{ number_format($subtotalPagina, 2) }}</strong>
                                </td>
                                <td></td>
                            </tr>
                            {{-- Total del período completo --}}
                            <tr class="table-success">
                                <td colspan="6" class="text-end">
                                    <strong>TOTAL PERÍODO COMPLETO ({{ $totalOrdenes }} órdenes):</strong>
                                </td>
                                <td class="text-end">
                                    <strong class="text-success fs-6">
                                        S/. {{ number_format($totalPeriodo, 2) }}
                                    </strong>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>

                @if ($ordenes->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4 px-3">
                        <div class="text-muted small">
                            Mostrando {{ $ordenes->firstItem() }} a {{ $ordenes->lastItem() }}
                            de {{ $ordenes->total() }} registros
                        </div>
                        <div>
                            {{ $ordenes->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
