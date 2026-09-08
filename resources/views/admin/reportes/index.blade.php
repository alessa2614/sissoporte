@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Reportes</h3>
    </div>

    {{-- FILTROS --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.reportes.index') }}" method="GET">
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
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="">-- Todos --</option>
                            <option value="recibido" {{ $estado == 'recibido' ? 'selected' : '' }}>Recibido
                            </option>
                            <option value="en_revision" {{ $estado == 'en_revision' ? 'selected' : '' }}>En Revisión
                            </option>
                            <option value="esperando_aprobacion" {{ $estado == 'esperando_aprobacion' ? 'selected' : '' }}>
                                Esp. Aprobación</option>
                            <option value="en_reparacion" {{ $estado == 'en_reparacion' ? 'selected' : '' }}>En
                                Reparación</option>
                            <option value="listo" {{ $estado == 'listo' ? 'selected' : '' }}>Listo</option>
                            <option value="entregado" {{ $estado == 'entregado' ? 'selected' : '' }}>Entregado
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ESTADÍSTICAS --}}
    <div class="row mb-4 g-3">
        <div class="col-md-2">
            <div class="card text-center h-100" style="border-top:3px solid #0d6efd !important;">
                <div class="card-body py-3">
                    <h2 class="fw-bold text-primary mb-0">{{ $stats['total_ordenes'] }}</h2>
                    <small class="text-muted">Total órdenes</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center h-100" style="border-top:3px solid #198754 !important;">
                <div class="card-body py-3">
                    <h2 class="fw-bold text-success mb-0">{{ $stats['entregadas'] }}</h2>
                    <small class="text-muted">Entregadas</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center h-100" style="border-top:3px solid #ffc107 !important;">
                <div class="card-body py-3">
                    <h2 class="fw-bold text-warning mb-0">{{ $stats['en_proceso'] }}</h2>
                    <small class="text-muted">En proceso</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center h-100" style="border-top:3px solid #198754 !important;">
                <div class="card-body py-3">
                    <h4 class="fw-bold text-success mb-0">
                        S/. {{ number_format($stats['total_ingresos'], 2) }}
                    </h4>
                    <small class="text-muted">Ingresos</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center h-100" style="border-top:3px solid #0d6efd !important;">
                <div class="card-body py-3">
                    <h4 class="fw-bold text-primary mb-0">
                        S/. {{ number_format($stats['total_servicios'], 2) }}
                    </h4>
                    <small class="text-muted">Servicios</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center h-100" style="border-top:3px solid #ffc107 !important;">
                <div class="card-body py-3">
                    <h4 class="fw-bold text-warning mb-0">
                        S/. {{ number_format($stats['total_adicionales'], 2) }}
                    </h4>
                    <small class="text-muted">Adicionales</small>
                </div>
            </div>
        </div>
    </div>

    {{-- BOTONES EXPORTAR --}}
    <div class="card mb-4">
        <div class="card-body d-flex align-items-center gap-3">
            <span class="text-muted small me-1">
                <i class="bi bi-download me-1"></i> Exportar:
            </span>
            <a href="{{ route('admin.reportes.pdf', ['desde' => $desde, 'hasta' => $hasta, 'estado' => $estado]) }}"
                class="btn btn-danger" target="_blank">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Descargar PDF
            </a>
            <a href="{{ route('admin.reportes.excel', ['desde' => $desde, 'hasta' => $hasta, 'estado' => $estado]) }}"
                class="btn btn-success">
                <i class="bi bi-file-earmark-spreadsheet-fill me-1"></i> Descargar Excel
            </a>
        </div>
    </div>

    {{-- TABLA PREVIA --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-table me-1"></i> Vista previa
            </h4>
            <span class="badge bg-secondary">
                {{ $ordenes->count() }} registro{{ $ordenes->count() !== 1 ? 's' : '' }}
                &nbsp;·&nbsp;
                {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }}
                al
                {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Código</th>
                            <th>Cliente</th>
                            <th>Equipo</th>
                            <th>Técnico</th>
                            <th>Estado</th>
                            <th class="text-end">Servicios</th>
                            <th class="text-end">Adicionales</th>
                            <th class="text-end">Total</th>
                            <th class="pe-3">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ordenes as $orden)
                            <tr>
                                <td class="ps-3">
                                    <a href="{{ route('admin.ordenes.show', $orden->id) }}"
                                        class="fw-bold text-decoration-none">
                                        {{ $orden->codigo }}
                                    </a>
                                </td>
                                <td>{{ $orden->cliente->nombre }}</td>
                                <td>{{ $orden->tipoEquipo->nombre }}</td>
                                <td>{{ $orden->tecnico->name ?? '—' }}</td>
                                <td>{!! $orden->badgeEstado() !!}</td>
                                <td class="text-end">
                                    S/. {{ number_format($orden->servicios->sum('precio'), 2) }}
                                </td>
                                <td class="text-end">
                                    @php $adic = $orden->adicionales->where('estado','aprobado')->sum('costo') @endphp
                                    {{ $adic > 0 ? 'S/. ' . number_format($adic, 2) : '—' }}
                                </td>
                                <td class="text-end">
                                    <strong class="text-success">
                                        S/. {{ number_format($orden->total_final ?? 0, 2) }}
                                    </strong>
                                </td>
                                <td class="pe-3 text-muted small">
                                    {{ $orden->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                    No hay registros en este período.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
