@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Detalle de Garantía</h3>
            <a href="{{ route('admin.garantias.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">

        {{-- INFO DE LA GARANTÍA --}}
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h4>Información de la Garantía</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width:45%">Orden:</th>
                            <td>
                                <a href="{{ route('admin.ordenes.show', $garantia->orden->id) }}">
                                    <strong>{{ $garantia->orden->codigo }}</strong>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Cliente:</th>
                            <td>
                                {{ $garantia->orden->cliente->nombre }}<br>
                                <small class="text-muted">{{ $garantia->orden->cliente->celular }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Equipo:</th>
                            <td>
                                {{ $garantia->orden->tipoEquipo->nombre }}
                                @if($garantia->orden->marca)
                                    — {{ $garantia->orden->marca }} {{ $garantia->orden->modelo }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Técnico:</th>
                            <td>{{ $garantia->orden->tecnico->name ?? '—' }}</td>
                        </tr>
                        <tr><td colspan="2"><hr class="my-1"></td></tr>
                        <tr>
                            <th>Fecha inicio:</th>
                            <td>{{ \Carbon\Carbon::parse($garantia->fecha_inicio)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Fecha vence:</th>
                            <td>{{ \Carbon\Carbon::parse($garantia->fecha_fin)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Días de garantía:</th>
                            <td>{{ $garantia->dias_garantia }} días</td>
                        </tr>
                        <tr>
                            <th>Días restantes:</th>
                            <td>
                                @php
                                    $hoy = \Carbon\Carbon::today();
                                    $fin = \Carbon\Carbon::parse($garantia->fecha_fin);
                                    $restantes = $hoy->diffInDays($fin, false);
                                @endphp
                                @if($garantia->estado === 'vigente' && $restantes > 0)
                                    <strong class="text-success">{{ $restantes }} días</strong>
                                @elseif($garantia->estado === 'vigente')
                                    <strong class="text-danger">Vence hoy</strong>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                @if($garantia->estado === 'vigente')
                                    <span class="badge bg-success fs-6">✅ Vigente</span>
                                @elseif($garantia->estado === 'vencida')
                                    <span class="badge bg-secondary fs-6">⏰ Vencida</span>
                                @else
                                    <span class="badge bg-danger fs-6">🔧 Usada</span>
                                @endif
                            </td>
                        </tr>
                        @if($garantia->observacion)
                            <tr>
                                <th>Observación:</th>
                                <td>{{ $garantia->observacion }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
                <div class="card-footer d-flex gap-2">
                    @if($garantia->estado === 'vigente')
                        <button type="button" class="btn btn-warning btn-sm"
                            data-bs-toggle="modal" data-bs-target="#modalUsada">
                            <i class="bi bi-shield-x"></i> Marcar como usada
                        </button>
                    @endif
                    {{-- Nueva orden para el mismo cliente (reclamo de garantía) --}}
                    @if($garantia->estado === 'vigente')
                        <a href="{{ route('admin.ordenes.create') }}?cliente_id={{ $garantia->orden->cliente_id }}"
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Registrar reclamo (nueva orden)
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- RESUMEN DE LO QUE SE HIZO — para referencia del técnico --}}
        <div class="col-md-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">¿Qué se hizo en esta reparación?</h4>
                    <span class="badge bg-secondary">
                        <i class="bi bi-info-circle"></i> Solo lectura — referencia para reclamos
                    </span>
                </div>
                <div class="card-body">

                    {{-- Alerta explicativa --}}
                    <div class="alert alert-light border mb-3 py-2">
                        <small class="text-muted">
                            <i class="bi bi-lightbulb text-warning"></i>
                            Este resumen sirve para saber exactamente qué trabajos se realizaron
                            y qué está cubierto si el cliente regresa con un reclamo dentro del
                            período de garantía.
                        </small>
                    </div>

                    {{-- Servicios del catálogo --}}
                    <h6 class="fw-bold mb-2">Servicios realizados:</h6>
                    @forelse($garantia->orden->servicios as $s)
                        <div class="d-flex justify-content-between mb-2 align-items-center">
                            <span><i class="bi bi-check-circle text-success me-1"></i>{{ $s->servicio->nombre }}</span>
                            <strong class="text-nowrap ms-2">S/. {{ number_format($s->precio, 2) }}</strong>
                        </div>
                        @if($s->observacion)
                            <small class="text-muted ms-3 d-block mb-1">{{ $s->observacion }}</small>
                        @endif
                    @empty
                        <p class="text-muted fst-italic">
                            No se registraron servicios del catálogo en esta orden.
                        </p>
                    @endforelse

                    {{-- Adicionales aprobados --}}
                    @if($garantia->orden->adicionales->where('estado','aprobado')->count() > 0)
                        <hr>
                        <h6 class="fw-bold mb-2">Trabajos adicionales aprobados:</h6>
                        @foreach($garantia->orden->adicionales->where('estado','aprobado') as $a)
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-plus-circle text-primary me-1"></i>{{ $a->descripcion }}</span>
                                <strong class="text-nowrap ms-2">S/. {{ number_format($a->costo, 2) }}</strong>
                            </div>
                        @endforeach
                    @endif

                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total pagado por el cliente:</strong>
                        <strong class="text-success fs-5">
                            S/. {{ number_format($garantia->orden->total_final ?? 0, 2) }}
                        </strong>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- MODAL: Marcar como usada --}}
    @if($garantia->estado === 'vigente')
        <div class="modal fade" id="modalUsada" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-shield-x text-warning"></i> Marcar garantía como usada
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.garantias.usar', $garantia->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body">
                            <p>
                                El cliente <strong>{{ $garantia->orden->cliente->nombre }}</strong>
                                ha presentado un reclamo de garantía por el equipo
                                <strong>{{ $garantia->orden->tipoEquipo->nombre }}</strong>.
                            </p>
                            <div class="form-group">
                                <label>Descripción del reclamo (opcional)</label>
                                <textarea name="observacion" class="form-control" rows="3"
                                    placeholder="Ej: El cliente regresó indicando que el equipo volvió a apagarse..."></textarea>
                            </div>
                            <div class="alert alert-warning mt-3 mb-0">
                                <small>
                                    <i class="bi bi-exclamation-triangle"></i>
                                    Luego de marcarla como usada, registra una
                                    <strong>nueva orden</strong> para hacer seguimiento al reclamo.
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-shield-x"></i> Confirmar reclamo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

@endsection