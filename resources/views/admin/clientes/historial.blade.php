@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Historial — {{ $cliente->nombre }}</h3>
            <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver a Clientes
            </a>
        </div>
    </div>

    {{-- TARJETA CLIENTE --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="bi bi-person-circle" style="font-size:3.5rem; color:#435ebe;"></i>
                </div>
                <div class="col-md-4">
                    <h4 class="mb-1">{{ $cliente->nombre }}</h4>
                    <p class="mb-0 text-muted">
                        <i class="bi bi-phone"></i> {{ $cliente->celular }}
                        @if($cliente->email)
                            &nbsp;·&nbsp;
                            <i class="bi bi-envelope"></i> {{ $cliente->email }}
                        @endif
                    </p>
                    @if($cliente->direccion)
                        <p class="mb-0 text-muted">
                            <i class="bi bi-geo-alt"></i> {{ $cliente->direccion }}
                        </p>
                    @endif
                    <small class="text-muted">
                        Cliente desde: {{ $cliente->created_at->format('d/m/Y') }}
                    </small>
                </div>

                {{-- ESTADÍSTICAS --}}
                <div class="col-md-6 ms-auto">
                    <div class="row text-center g-2">
                        <div class="col-3">
                            <div class="border rounded p-2">
                                <h3 class="text-primary mb-0">{{ $stats['total_ordenes'] }}</h3>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="border rounded p-2">
                                <h3 class="text-success mb-0">{{ $stats['entregadas'] }}</h3>
                                <small class="text-muted">Entregadas</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="border rounded p-2">
                                <h3 class="text-warning mb-0">{{ $stats['en_proceso'] }}</h3>
                                <small class="text-muted">En proceso</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="border rounded p-2">
                                <h5 class="text-success mb-0">
                                    S/. {{ number_format($stats['total_gastado'], 2) }}
                                </h5>
                                <small class="text-muted">Gastado</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ÓRDENES --}}
    @forelse($ordenes as $orden)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
                <div>
                    <strong>{{ $orden->codigo }}</strong>
                    &nbsp;·&nbsp;
                    {{ $orden->tipoEquipo->nombre }}
                    @if($orden->marca)
                        — {{ $orden->marca }} {{ $orden->modelo }}
                    @endif
                    &nbsp;·&nbsp;
                    <small class="text-muted">{{ $orden->created_at->format('d/m/Y H:i') }}</small>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    {!! $orden->badgeEstado() !!}

                    @if($orden->garantia && $orden->garantia->estado === 'vigente')
                        <span class="badge bg-success">
                            🛡️ Garantía vigente
                        </span>
                    @endif

                    <a href="{{ route('admin.ordenes.show', $orden->id) }}"
                        class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye"></i> Ver
                    </a>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="row">

                    {{-- Columna 1: Info --}}
                    <div class="col-md-4">
                        <p class="mb-1">
                            <strong>Problema:</strong><br>
                            <span class="text-muted">{{ $orden->descripcion }}</span>
                        </p>
                        <p class="mb-1">
                            <strong>Técnico:</strong>
                            {{ $orden->tecnico->name ?? '—' }}
                        </p>
                        <p class="mb-0">
                            @if($orden->estado === 'entregado')
                                <strong>Total pagado:</strong>
                                <span class="text-success fw-bold">
                                    S/. {{ number_format($orden->total_final ?? 0, 2) }}
                                </span>
                            @else
                                <strong>Costo estimado:</strong>
                                S/. {{ number_format($orden->costo_estimado ?? 0, 2) }}
                            @endif
                        </p>
                    </div>

                    {{-- Columna 2: Servicios --}}
                    <div class="col-md-4">
                        <strong>Servicios realizados:</strong>
                        @if($orden->servicios->count() > 0)
                            <ul class="mb-0 mt-1 ps-3" style="font-size:13px;">
                                @foreach($orden->servicios as $s)
                                    <li>
                                        {{ $s->servicio->nombre }}
                                        <span class="text-muted">
                                            — S/. {{ number_format($s->precio, 2) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0 mt-1" style="font-size:13px;">
                                Sin servicios registrados
                            </p>
                        @endif

                        {{-- Adicionales aprobados --}}
                        @php $adicionales = $orden->adicionales->where('estado','aprobado'); @endphp
                        @if($adicionales->count() > 0)
                            <strong class="mt-2 d-block">Adicionales aprobados:</strong>
                            <ul class="mb-0 mt-1 ps-3" style="font-size:13px;">
                                @foreach($adicionales as $a)
                                    <li>
                                        {{ $a->descripcion }}
                                        <span class="text-muted">
                                            — S/. {{ number_format($a->costo, 2) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    {{-- Columna 3: Garantía --}}
                    <div class="col-md-4">
                        <strong>Garantía:</strong>
                        @if($orden->garantia)
                            <ul class="mb-0 mt-1 ps-3" style="font-size:13px;">
                                <li>Inicio: {{ $orden->garantia->fecha_inicio->format('d/m/Y') }}</li>
                                <li>Vence: {{ $orden->garantia->fecha_fin->format('d/m/Y') }}</li>
                                <li>
                                    Estado:
                                    @if($orden->garantia->estado === 'vigente')
                                        <span class="text-success fw-bold">
                                            Vigente ({{ $orden->garantia->diasRestantes() }} días)
                                        </span>
                                    @elseif($orden->garantia->estado === 'vencida')
                                        <span class="text-muted">Vencida</span>
                                    @else
                                        <span class="text-danger">Usada</span>
                                    @endif
                                </li>
                            </ul>
                        @else
                            <p class="text-muted mb-0 mt-1" style="font-size:13px;">
                                Sin garantía
                            </p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-inbox" style="font-size:3rem;"></i>
                <p class="mt-2">Este cliente no tiene órdenes registradas.</p>
                <a href="{{ route('admin.ordenes.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus"></i> Crear primera orden
                </a>
            </div>
        </div>
    @endforelse

@endsection