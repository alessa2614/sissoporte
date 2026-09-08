@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Nueva Orden de Servicio</h3>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Corrige los siguientes errores:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <style>
        .tipo-radio {
            display: none;
        }

        .tipo-label {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 15px 18px;
            border: 2px solid #dee2e6;
            border-radius: 12px;
            cursor: pointer;
            background: #fff;
            transition: all .2s;
            user-select: none;
            color: #1a1a2e;
            width: 100%;
        }

        .tipo-label:hover {
            border-color: #adb5bd;
            background: #f8f9fa;
        }

        #tipo_espera:checked+.tipo-label {
            border-color: #F07820;
            background: #fff8f2;
            box-shadow: 0 0 0 3px rgba(240, 120, 32, 0.15);
        }

        #tipo_deja:checked+.tipo-label {
            border-color: #0d6efd;
            background: #f0f5ff;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.12);
        }

        .tipo-icono {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .tipo-titulo {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 2px;
        }

        .tipo-sub {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.45;
        }

        .tipo-sub strong {
            color: #374151;
        }

        .tipo-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #ced4da;
            margin-left: auto;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .2s;
        }

        .tipo-dot::after {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #fff;
            opacity: 0;
            transition: opacity .15s;
        }

        #tipo_espera:checked+.tipo-label .tipo-dot {
            background: #F07820;
            border-color: #F07820;
        }

        #tipo_deja:checked+.tipo-label .tipo-dot {
            background: #0d6efd;
            border-color: #0d6efd;
        }

        #tipo_espera:checked+.tipo-label .tipo-dot::after,
        #tipo_deja:checked+.tipo-label .tipo-dot::after {
            opacity: 1;
        }
    </style>

    <form action="{{ route('admin.ordenes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">

            {{-- ── TIPO DE ATENCIÓN ─────────────────────────────── --}}
            <div class="col-md-12">
                <div class="card border-0 shadow-sm mb-1">
                    <div class="card-body py-3">
                        <label class="fw-bold mb-3 d-block" style="font-size:14px; color:#1a1a2e;">
                            <i class="bi bi-ui-checks-grid me-2" style="color:#F07820;"></i>
                            Tipo de atención <span class="text-danger">*</span>
                        </label>
                        <div class="row g-3">

                            <div class="col-md-6">
                                <input type="radio" class="tipo-radio" name="tipo_atencion" id="tipo_espera"
                                    value="espera" {{ old('tipo_atencion', 'deja') === 'espera' ? 'checked' : '' }}>
                                <label class="tipo-label" for="tipo_espera">
                                    <div class="tipo-icono" style="background:#fff4ec; color:#F07820;">
                                        <i class="bi bi-hourglass-split"></i>
                                    </div>
                                    <div>
                                        <div class="tipo-titulo"> Cliente espera</div>
                                        <div class="tipo-sub">
                                            Formateo, instalación, diagnóstico rápido.<br>
                                            <strong>Sin Ticket 1</strong> — solo Recibo al terminar.
                                        </div>
                                    </div>
                                    <div class="tipo-dot"></div>
                                </label>
                            </div>

                            <div class="col-md-6">
                                <input type="radio" class="tipo-radio" name="tipo_atencion" id="tipo_deja" value="deja"
                                    {{ old('tipo_atencion', 'deja') === 'deja' ? 'checked' : '' }}>
                                <label class="tipo-label" for="tipo_deja">
                                    <div class="tipo-icono" style="background:#eef3fa; color:#0d6efd;">
                                        <i class="bi bi-box-arrow-in-down"></i>
                                    </div>
                                    <div>
                                        <div class="tipo-titulo">📥 Cliente deja el equipo</div>
                                        <div class="tipo-sub">
                                            Reparación de horas o días.<br>
                                            <strong>Genera Ticket 1</strong> al ingresar + Recibo al entregar.
                                        </div>
                                    </div>
                                    <div class="tipo-dot"></div>
                                </label>
                            </div>

                        </div>
                        @error('tipo_atencion')
                            <div class="text-danger mt-2" style="font-size:.85em;">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- DATOS DEL CLIENTE --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="bi bi-person"></i> Datos del Cliente</h4>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="cliente_id" id="cliente_id"
                            value="{{ old('cliente_id', $clienteSeleccionado ?? '') }}">

                        <div class="form-group mb-3">
                            <label>Buscar cliente por nombre o celular (*)</label>
                            <div class="position-relative">
                                <input type="text" id="buscarCliente"
                                    class="form-control @error('cliente_id') is-invalid @enderror"
                                    placeholder="Escribe nombre o celular..." autocomplete="off">
                                <ul id="sugerenciasCliente" class="list-group position-absolute w-100 shadow-sm d-none"
                                    style="z-index:9999; max-height:220px; overflow-y:auto; top:100%;">
                                </ul>
                            </div>
                            @error('cliente_id')
                                <div class="text-danger mt-1" style="font-size:.875em">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="infoCliente" class="alert alert-info py-2 d-none">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="bi bi-person-check"></i>
                                    <span id="textoCliente"></span>
                                </span>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="limpiarCliente()">
                                    <i class="bi bi-x"></i> Cambiar
                                </button>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('admin.clientes.create') }}" class="btn btn-outline-secondary btn-sm"
                                target="_blank">
                                <i class="bi bi-plus"></i> Registrar nuevo cliente
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DATOS DEL EQUIPO --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="bi bi-laptop"></i> Datos del Equipo</h4>
                    </div>
                    <div class="card-body">

                        <div class="form-group mb-3">
                            <label>Tipo de equipo (*)</label>
                            <select name="tipo_equipo_id"
                                class="form-control @error('tipo_equipo_id') is-invalid @enderror">
                                <option value="">-- Seleccione tipo --</option>
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id }}"
                                        {{ old('tipo_equipo_id') == $tipo->id ? 'selected' : '' }}>
                                        {{ $tipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_equipo_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Marca</label>
                                    <input type="text" name="marca" class="form-control"
                                        placeholder="Ej: HP, Dell, Samsung" value="{{ old('marca') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Modelo</label>
                                    <input type="text" name="modelo" class="form-control"
                                        placeholder="Ej: Pavilion 15" value="{{ old('modelo') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>Foto del equipo (opcional)</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <small class="text-muted">
                                La foto no se pierde si hay un error — puedes volver a subirla.
                            </small>
                        </div>

                    </div>
                </div>
            </div>

            {{-- PROBLEMA Y TÉCNICO --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="bi bi-tools"></i> Servicio</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label>Descripción del problema</label>
                                    <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3"
                                        placeholder="Describe el problema que reporta el cliente">{{ old('descripcion') }}</textarea>
                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Técnico asignado</label>
                                    <select name="tecnico_id" class="form-control">
                                        <option value="">-- Sin asignar --</option>
                                        @foreach ($tecnicos as $tecnico)
                                            <option value="{{ $tecnico->id }}"
                                                {{ old('tecnico_id') == $tecnico->id ? 'selected' : '' }}>
                                                {{ $tecnico->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Costo estimado (S/.)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">S/.</span>
                                        <input type="number" name="costo_estimado" id="costo_estimado"
                                            class="form-control" step="0.01" min="0" placeholder="0.00"
                                            value="{{ old('costo_estimado', 0) }}" readonly>
                                    </div>
                                    <small class="text-muted">
                                        Se calcula automático según los servicios agregados
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SERVICIOS A REALIZAR --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="bi bi-list-check"></i> Servicios a Realizar</h4>
                    </div>
                    <div class="card-body">

                        <div class="row g-2 align-items-end mb-3">
                            <div class="col-md-4">
                                <label>Servicio</label>
                                <select id="nuevoServicio" class="form-control">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($servicios as $s)
                                        <option value="{{ $s->id }}" data-nombre="{{ $s->nombre }}"
                                            data-precio="{{ $s->precio_base }}">
                                            {{ $s->nombre }} — S/. {{ number_format($s->precio_base, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Precio (S/.)</label>
                                <div class="input-group">
                                    <span class="input-group-text">S/.</span>
                                    <input type="number" id="nuevoPrecio" class="form-control" step="0.01"
                                        min="0" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Observación (opcional)</label>
                                <input type="text" id="nuevaObservacion" class="form-control"
                                    placeholder="Ej: incluye mano de obra">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-success w-100" onclick="agregarServicio()">
                                    <i class="bi bi-plus-circle"></i> Agregar
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Servicio</th>
                                        <th style="width:130px">Precio</th>
                                        <th>Observación</th>
                                        <th style="width:60px" class="text-center">Quitar</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoServicios"></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-end"><strong>TOTAL ESTIMADO:</strong></td>
                                        <td><strong>S/. <span id="totalEstimado">0.00</span></strong></td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div id="sinServicios" class="alert alert-info py-2">
                            <i class="bi bi-info-circle"></i>
                            Agrega los servicios que se realizarán.
                            También puedes agregar más después desde la orden.
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- BOTONES --}}
        <div class="row mt-2 mb-4">
            <div class="col-md-12">
                <a href="{{ route('admin.ordenes.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary" id="btnGuardar">
                    <i class="bi bi-save" id="btnIcon"></i>
                    <span id="btnTexto">Registrar Orden y Generar Ticket</span>
                </button>
            </div>
        </div>

    </form>

    <script>
        const serviciosGuardados = @json(old('servicios', []));
        const clientes = @json($clientesJs);
        let contador = 0;

        // ── Cambiar texto del botón según tipo ───────────────────────
        document.querySelectorAll('.tipo-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                const btnTexto = document.getElementById('btnTexto');
                const btnIcon = document.getElementById('btnIcon');
                if (this.value === 'espera') {
                    btnTexto.textContent = 'Registrar Orden — Cliente en Espera';
                    btnIcon.className = 'bi bi-hourglass-split';
                } else {
                    btnTexto.textContent = 'Registrar Orden y Generar Ticket';
                    btnIcon.className = 'bi bi-save';
                }
            });
        });

        // ── Init ─────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {

            // Restaurar servicios si hubo error de validación
            if (serviciosGuardados && serviciosGuardados.length > 0) {
                const selectEl = document.getElementById('nuevoServicio');
                const mapa = {};
                [...selectEl.options].forEach(op => {
                    if (op.value) mapa[op.value] = op.dataset.nombre;
                });
                serviciosGuardados.forEach(s => {
                    if (s.servicio_id && s.precio !== undefined) {
                        const nombre = mapa[s.servicio_id] || 'Servicio #' + s.servicio_id;
                        _insertarFila(s.servicio_id, nombre, s.precio, s.observacion || '');
                    }
                });
                document.getElementById('sinServicios').style.display = 'none';
                recalcularTotal();
            }

            // Restaurar cliente
            const idInicial = document.getElementById('cliente_id').value;
            if (idInicial) {
                const c = clientes.find(c => c.id == idInicial);
                if (c) mostrarCliente(c);
            }

            // Aplicar texto del botón al cargar
            const tipoActual = document.querySelector('.tipo-radio:checked');
            if (tipoActual?.value === 'espera') {
                document.getElementById('btnTexto').textContent = 'Registrar Orden — Cliente en Espera';
                document.getElementById('btnIcon').className = 'bi bi-hourglass-split';
            }
        });

        // ── Buscador de cliente ──────────────────────────────────────
        const inputBuscar = document.getElementById('buscarCliente');
        const listaSugerencias = document.getElementById('sugerenciasCliente');

        inputBuscar.addEventListener('input', function() {
            const q = this.value.trim().toLowerCase();
            listaSugerencias.innerHTML = '';
            if (q.length < 2) {
                listaSugerencias.classList.add('d-none');
                return;
            }

            const resultados = clientes.filter(c =>
                c.nombre.toLowerCase().includes(q) || c.celular.includes(q)
            ).slice(0, 8);

            if (!resultados.length) {
                listaSugerencias.innerHTML = '<li class="list-group-item text-muted">Sin resultados</li>';
                listaSugerencias.classList.remove('d-none');
                return;
            }

            resultados.forEach(c => {
                const li = document.createElement('li');
                li.className =
                    'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                li.style.cursor = 'pointer';
                li.innerHTML = `<span><strong>${c.nombre}</strong></span>
                                <span class="text-muted small">${c.celular}</span>`;
                li.addEventListener('click', () => seleccionarCliente(c));
                listaSugerencias.appendChild(li);
            });
            listaSugerencias.classList.remove('d-none');
        });

        document.addEventListener('click', e => {
            if (!inputBuscar.contains(e.target) && !listaSugerencias.contains(e.target))
                listaSugerencias.classList.add('d-none');
        });

        function seleccionarCliente(c) {
            document.getElementById('cliente_id').value = c.id;
            listaSugerencias.classList.add('d-none');
            inputBuscar.value = '';
            mostrarCliente(c);
        }

        function mostrarCliente(c) {
            document.getElementById('textoCliente').textContent =
                `${c.nombre}  ·  ${c.celular}` + (c.correo ? `  ·  ${c.correo}` : '');
            document.getElementById('infoCliente').classList.remove('d-none');
            inputBuscar.style.display = 'none';
        }

        function limpiarCliente() {
            document.getElementById('cliente_id').value = '';
            document.getElementById('infoCliente').classList.add('d-none');
            inputBuscar.style.display = '';
            inputBuscar.value = '';
            inputBuscar.focus();
        }

        // ── Servicios ────────────────────────────────────────────────
        document.getElementById('nuevoServicio').addEventListener('change', function() {
            const op = this.options[this.selectedIndex];
            document.getElementById('nuevoPrecio').value = op.dataset.precio || '';
        });

        function agregarServicio() {
            const select = document.getElementById('nuevoServicio');
            const precio = document.getElementById('nuevoPrecio').value;
            const observacion = document.getElementById('nuevaObservacion').value;

            if (!select.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Selecciona un servicio',
                    timer: 1500,
                    showConfirmButton: false
                });
                return;
            }
            if (!precio || parseFloat(precio) < 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ingresa un precio válido',
                    timer: 1500,
                    showConfirmButton: false
                });
                return;
            }

            _insertarFila(select.value, select.options[select.selectedIndex].dataset.nombre, precio, observacion);
            select.value = '';
            document.getElementById('nuevoPrecio').value = '';
            document.getElementById('nuevaObservacion').value = '';
            document.getElementById('sinServicios').style.display = 'none';
            recalcularTotal();
        }

        function _insertarFila(id, nombre, precio, observacion) {
            const idx = contador++;
            const tbody = document.getElementById('cuerpoServicios');
            const fila = document.createElement('tr');
            fila.id = 'fila_' + idx;
            fila.innerHTML = `
                <td>${tbody.children.length + 1}</td>
                <td>
                    ${nombre}
                    <input type="hidden" name="servicios[${idx}][servicio_id]" value="${id}">
                </td>
                <td>
                    S/. ${parseFloat(precio).toFixed(2)}
                    <input type="hidden" name="servicios[${idx}][precio]" value="${precio}">
                </td>
                <td>
                    ${observacion || '—'}
                    <input type="hidden" name="servicios[${idx}][observacion]" value="${observacion}">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm"
                        onclick="quitarServicio(${idx})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>`;
            tbody.appendChild(fila);
        }

        function quitarServicio(idx) {
            document.getElementById('fila_' + idx)?.remove();
            renumerarFilas();
            recalcularTotal();
            if (!document.getElementById('cuerpoServicios').children.length)
                document.getElementById('sinServicios').style.display = 'block';
        }

        function renumerarFilas() {
            const filas = document.getElementById('cuerpoServicios').children;
            for (let i = 0; i < filas.length; i++) filas[i].cells[0].textContent = i + 1;
        }

        function recalcularTotal() {
            let total = 0;
            document.querySelectorAll('[name$="[precio]"]').forEach(i => total += parseFloat(i.value) || 0);
            document.getElementById('totalEstimado').textContent = total.toFixed(2);
            document.getElementById('costo_estimado').value = total.toFixed(2);
        }
    </script>
@endsection
