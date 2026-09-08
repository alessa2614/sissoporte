@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h3>Orden: <strong>{{ $orden->codigo }}</strong>
                {!! $orden->badgeTipoAtencion() !!}
            </h3>
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <a href="{{ route('admin.ordenes.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>

                @if (!in_array($orden->estado, ['entregado']))
                    <a href="{{ route('admin.ordenes.edit', $orden->id) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                @endif

                {{-- Ticket 1 solo si el cliente dejó el equipo --}}
                @if ($orden->tipo_atencion === 'deja')
                    <a href="{{ route('admin.ticket1', $orden->id) }}" class="btn btn-outline-dark btn-sm" target="_blank">
                        <i class="bi bi-printer"></i> Ticket Ingreso
                    </a>
                @endif

                {{-- Ticket 2 cuando está listo o entregado --}}
                @if (in_array($orden->estado, ['listo', 'entregado']))
                    <a href="{{ route('admin.ticket2', $orden->id) }}" class="btn btn-success btn-sm" target="_blank">
                        <i class="bi bi-printer"></i> Recibo Final
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- ══ BANNER CLIENTE EN ESPERA ══ --}}
    @if ($orden->tipo_atencion === 'espera' && $orden->estado !== 'entregado')
        <div class="alert border-0 shadow-sm mb-4"
            style="background: linear-gradient(135deg, #fff8f0, #ffe8cc); border-left: 5px solid #F07820 !important;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span style="font-size:2.2rem;"></span>
                    <div>
                        <h5 class="mb-1 fw-bold" style="color:#F07820;">Cliente en espera</h5>
                        <small class="text-muted">
                            Cuando el técnico termine, usa <strong>"Marcar como Entregado"</strong>.
                            Si el cliente se va antes, usa <strong>"El cliente se fue"</strong>
                            para generar su ticket de ingreso.
                        </small>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">

                    {{-- Entregar directo (ya existía) --}}
                    <button type="button" class="btn btn-lg fw-bold px-4"
                        style="background:#F07820; color:#fff; border:none;" onclick="confirmarEntregaDirecta()">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Marcar como Entregado
                    </button>

                    {{-- NUEVO: cliente se fue --}}
                    <form id="formClienteSesFue" action="{{ route('admin.ordenes.cambiar_tipo', $orden->id) }}"
                        method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="tipo_atencion" value="deja">
                        <button type="button" class="btn btn-lg btn-outline-secondary" onclick="confirmarClienteSesFue()">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            El cliente se fue
                        </button>
                    </form>

                </div>
            </div>
        </div>

        {{-- Form oculto para entrega directa --}}
        <form id="formEntregaDirecta" action="{{ route('admin.ordenes.entregar_directo', $orden->id) }}" method="POST"
            style="display:none;">
            @csrf @method('PUT')
            <input type="hidden" name="dias_garantia" value="30">
        </form>
    @endif
    {{-- ══ BANNER ORDEN CERRADA ══ --}}
    @php
        $ordenCerrada = $orden->estado === 'entregado' || $orden->garantia !== null;
    @endphp

    @if ($ordenCerrada)
        <div
            class="alert bg-success-subtle border-0 shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <span style="font-size:2rem;">✅</span>
                <div>
                    <h5 class="mb-0 fw-bold">Orden cerrada</h5>
                    <small class="text-muted">
                        Este equipo fue entregado
                        @if ($orden->garantia)
                            y tiene garantía vigente hasta
                            <strong>{{ \Carbon\Carbon::parse($orden->garantia->fecha_fin)->format('d/m/Y') }}</strong>.
                        @else
                            .
                        @endif
                        No se pueden agregar más servicios ni adicionales.
                    </small>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @if ($orden->garantia)
                    <a href="{{ route('admin.garantias.show', $orden->garantia->id) }}"
                        class="btn btn-outline-success btn-sm">
                        <i class="bi bi-shield-check"></i> Ver Garantía
                    </a>
                @endif
                <a href="{{ route('admin.ordenes.create') }}?cliente_id={{ $orden->cliente_id }}"
                    class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Nueva orden para {{ $orden->cliente->nombre }}
                </a>
            </div>
        </div>
    @endif

    <div class="row">

        {{-- INFO PRINCIPAL --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Información de la Orden</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width:40%">Código:</th>
                            <td><strong>{{ $orden->codigo }}</strong></td>
                        </tr>
                        <tr>
                            <th>Tipo:</th>
                            <td>{!! $orden->badgeTipoAtencion() !!}</td>
                        </tr>
                        <tr>
                            <th>Cliente:</th>
                            <td>
                                {{ $orden->cliente->nombre }}<br>
                                <small class="text-muted">{{ $orden->cliente->celular }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Equipo:</th>
                            <td>
                                {{ $orden->tipoEquipo->nombre }}
                                @if ($orden->marca)
                                    — {{ $orden->marca }} {{ $orden->modelo }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Problema:</th>
                            <td>{{ $orden->descripcion }}</td>
                        </tr>
                        <tr>
                            <th>Técnico:</th>
                            <td>{{ $orden->tecnico->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>{!! $orden->badgeEstado() !!}</td>
                        </tr>
                        <tr>
                            <th>Costo estimado:</th>
                            <td>S/. {{ number_format($orden->costo_estimado ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Total final:</th>
                            <td>
                                @if ($orden->total_final)
                                    <strong>S/. {{ number_format($orden->total_final, 2) }}</strong>
                                @else
                                    <span class="text-muted">Por calcular</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Pago:</th>
                            <td>
                                @if ($orden->estado_pago == 'pagado')
                                    <span class="badge bg-success">Pagado</span>
                                @else
                                    <span class="badge bg-warning">Pendiente</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha:</th>
                            <td>{{ $orden->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- GARANTÍA --}}
            @if ($orden->garantia)
                <div class="card border-success mt-3">
                    <div class="card-header bg-success-subtle">
                        <h4 class="mb-0"><i class="bi bi-shield-check"></i> Garantía</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th style="width:45%">Días:</th>
                                <td>{{ $orden->garantia->dias_garantia }} días</td>
                            </tr>
                            <tr>
                                <th>Desde:</th>
                                <td>{{ \Carbon\Carbon::parse($orden->garantia->fecha_inicio)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Vence:</th>
                                <td>
                                    {{ \Carbon\Carbon::parse($orden->garantia->fecha_fin)->format('d/m/Y') }}
                                    @if (\Carbon\Carbon::parse($orden->garantia->fecha_fin)->isPast())
                                        <span class="badge bg-danger ms-1">Vencida</span>
                                    @else
                                        <span class="badge bg-success ms-1">Vigente</span>
                                    @endif
                                </td>
                            </tr>
                            @if ($orden->garantia->observacion)
                                <tr>
                                    <th>Nota:</th>
                                    <td>{{ $orden->garantia->observacion }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- HISTORIAL --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Historial de Estados</h4>
                </div>
                <div class="card-body">
                    @forelse ($orden->historial as $h)
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                    style="width:35px;height:35px;">
                                    <i class="bi bi-arrow-right text-white"></i>
                                </div>
                            </div>
                            <div>
                                <strong>{{ $h->estado_nuevo }}</strong>
                                @if ($h->observacion)
                                    <br><small class="text-muted">{{ $h->observacion }}</small>
                                @endif
                                <br><small class="text-muted">
                                    {{ $h->created_at->format('d/m/Y H:i') }}
                                    — {{ $h->usuario->name ?? 'Sistema' }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Sin historial aún.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- SERVICIOS --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Servicios Realizados</h4>
                    @if ($ordenCerrada)
                        <span class="badge bg-secondary"><i></i> Cerrado</span>
                    @endif
                </div>
                <div class="card-body">
                    @forelse ($orden->servicios as $s)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $s->servicio->nombre }}</span>
                            <strong>S/. {{ number_format($s->precio, 2) }}</strong>
                        </div>
                    @empty
                        <p class="text-muted">Sin servicios registrados.</p>
                    @endforelse
                    @if ($orden->servicios->count() > 0)
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Subtotal servicios:</strong>
                            <strong>S/. {{ number_format($orden->servicios->sum('precio'), 2) }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ADICIONALES --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Repuestos Adicionales</h4>
                    @if ($ordenCerrada)
                        <span class="badge bg-secondary"><i></i> Cerrado</span>
                    @endif
                </div>
                <div class="card-body">
                    @forelse ($orden->adicionales as $a)
                        <div class="d-flex justify-content-between mb-2 align-items-center">
                            <div>
                                <span>{{ $a->descripcion }}</span><br>
                                @if ($a->estado == 'aprobado')
                                    <span class="badge bg-success">Aprobado</span>
                                @elseif($a->estado == 'rechazado')
                                    <span class="badge bg-danger">Rechazado</span>
                                @else
                                    <span class="badge bg-warning">Pendiente</span>
                                @endif
                            </div>
                            <strong>S/. {{ number_format($a->costo, 2) }}</strong>
                        </div>
                    @empty
                        <p class="text-muted">Sin adicionales registrados.</p>
                    @endforelse
                    @if ($orden->adicionales->where('estado', 'aprobado')->count() > 0)
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Subtotal adicionales aprobados:</strong>
                            <strong>S/.
                                {{ number_format($orden->adicionales->where('estado', 'aprobado')->sum('costo'), 2) }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- FORMULARIOS: solo si la orden NO está cerrada --}}
        @if (!$ordenCerrada)

            {{-- AGREGAR SERVICIO --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Agregar Servicio</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.ordenes.agregar_servicio', $orden->id) }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label>Servicio (*)</label>
                                <select name="servicio_id" class="form-control" id="selectServicio">
                                    <option value="">-- Seleccione --</option>
                                    @foreach (\App\Models\CatalogoServicio::orderBy('nombre')->get() as $s)
                                        <option value="{{ $s->id }}" data-precio="{{ $s->precio_base }}">
                                            {{ $s->nombre }} — S/. {{ number_format($s->precio_base, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label>Precio cobrado (*)</label>
                                <div class="input-group">
                                    <span class="input-group-text">S/.</span>
                                    <input type="number" name="precio" id="inputPrecio" class="form-control"
                                        step="0.01" min="0" placeholder="0.00">
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Observación</label>
                                <input type="text" name="observacion" class="form-control" placeholder="Opcional">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus"></i> Agregar servicio
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- AGREGAR ADICIONAL --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Registrar Trabajo Adicional</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.ordenes.agregar_adicional', $orden->id) }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label>Descripción (*)</label>
                                <input type="text" name="descripcion" class="form-control"
                                    placeholder="Ej: Cambio de RAM 8GB DDR4">
                            </div>
                            <div class="form-group mb-3">
                                <label>Costo (*)</label>
                                <div class="input-group">
                                    <span class="input-group-text">S/.</span>
                                    <input type="number" name="costo" class="form-control" step="0.01"
                                        min="0" placeholder="0.00">
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Fecha de llamada al cliente</label>
                                <input type="date" name="fecha_llamada" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Observación</label>
                                <textarea name="observacion" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="bi bi-plus"></i> Registrar adicional
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ADICIONALES PENDIENTES --}}
            @if ($orden->adicionales->where('estado', 'pendiente')->count() > 0)
                <div class="col-md-12">
                    <div class="card border-warning">
                        <div class="card-header bg-warning">
                            <h4 class="mb-0">Adicionales pendientes de aprobación</h4>
                        </div>
                        <div class="card-body">
                            @foreach ($orden->adicionales->where('estado', 'pendiente') as $a)
                                <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                                    <div>
                                        <strong>{{ $a->descripcion }}</strong>
                                        <br><span class="text-muted">S/. {{ number_format($a->costo, 2) }}</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('admin.adicionales.aprobar', $a->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button class="btn btn-success btn-sm">
                                                <i class="bi bi-check"></i> Aprobado
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.adicionales.rechazar', $a->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="bi bi-x"></i> Rechazado
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="col-md-12">
                <div class="card border-0 bg-light">
                    <div class="card-body text-center py-5">
                        <div style="font-size:3rem;">🔒</div>
                        <h5 class="mt-2 text-muted">Esta orden está cerrada</h5>
                        <p class="text-muted mb-4">
                            El equipo ya fue entregado al cliente. Si el cliente trae el equipo nuevamente
                            por un problema diferente o fuera de garantía, registra una nueva orden.
                        </p>
                        <a href="{{ route('admin.ordenes.create') }}?cliente_id={{ $orden->cliente_id }}"
                            class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i>
                            Nueva orden para {{ $orden->cliente->nombre }}
                        </a>
                        <a href="{{ route('admin.clientes.historial', $orden->cliente_id) }}"
                            class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-clock-history"></i>
                            Ver historial del cliente
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </div>

    @if (!$ordenCerrada)
        <script>
            document.getElementById('selectServicio').addEventListener('change', function() {
                const precio = this.options[this.selectedIndex].dataset.precio;
                document.getElementById('inputPrecio').value = precio || '';
            });
        </script>
    @endif

    <script>
        function confirmarEntregaDirecta() {
            Swal.fire({
                title: '¿Marcar como Entregado?',
                html: `
                    <p>El equipo del cliente <strong>{{ $orden->cliente->nombre }}</strong> será marcado como entregado.</p>
                    <p>Se generará el <strong>Recibo Final</strong> y la <strong>garantía de 30 días</strong> automáticamente.</p>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#F07820',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-check-circle"></i> Sí, entregar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById('formEntregaDirecta').submit();
                }
            });
        }
    </script>
    <script>
        function confirmarEntregaDirecta() {
            Swal.fire({
                title: '¿Marcar como Entregado?',
                html: `
                <p>El equipo de <strong>{{ $orden->cliente->nombre }}</strong>
                será marcado como entregado.</p>
                <p>Se generará el <strong>Recibo Final</strong> y la
                <strong>garantía de 30 días</strong> automáticamente.</p>
            `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#F07820',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-check-circle"></i> Sí, entregar',
                cancelButtonText: 'Cancelar'
            }).then(r => {
                if (r.isConfirmed) document.getElementById('formEntregaDirecta').submit();
            });
        }

        function confirmarClienteSesFue() {
            Swal.fire({
                title: '¿El cliente se fue?',
                html: `
                <p>La orden <strong>{{ $orden->codigo }}</strong> cambiará a
                modalidad <strong>"Dejó el equipo"</strong>.</p>
                <p class="text-muted" style="font-size:.9rem">
                    Se imprimirá el <strong>Ticket de Ingreso</strong>
                    y se enviará WhatsApp al cliente avisando que su equipo
                    quedó registrado.
                </p>
            `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6c757d',
                cancelButtonColor: '#F07820',
                confirmButtonText: '<i class="bi bi-box-arrow-right"></i> Sí, se fue',
                cancelButtonText: 'Cancelar'
            }).then(r => {
                if (r.isConfirmed) document.getElementById('formClienteSesFue').submit();
            });
        }
    </script>
@endsection
