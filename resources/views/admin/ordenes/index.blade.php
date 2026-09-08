@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Órdenes de Servicio</h3>
    </div>

    <style>
        .search-wrapper {
            position: relative;
            max-width: 420px;
        }

        .search-wrapper .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #6366f1;
            font-size: 1rem;
            pointer-events: none;
        }

        .search-input {
            padding-left: 42px;
            padding-right: 40px;
            height: 42px;
            border: 2px solid #e0e7ff;
            border-radius: 12px;
            font-size: 0.92rem;
            background: #f8f9ff;
            transition: border-color .25s, box-shadow .25s, background .25s;
            outline: none;
            width: 100%;
        }

        .search-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, .12);
            background: #fff;
        }

        .search-input::placeholder {
            color: #a5b4fc;
        }

        .btn-clear-search {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a5b4fc;
            font-size: .85rem;
            cursor: pointer;
            padding: 2px 4px;
            border-radius: 6px;
            transition: color .2s, background .2s;
        }

        .btn-clear-search:hover {
            color: #6366f1;
            background: #e0e7ff;
        }

        #buscando-spinner {
            display: none;
            position: absolute;
            right: 36px;
            top: 50%;
            transform: translateY(-50%);
        }

        .spinner-ring {
            width: 16px;
            height: 16px;
            border: 2px solid #e0e7ff;
            border-top-color: #6366f1;
            border-radius: 50%;
            animation: spin .6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        #tabla-resultados.cargando tbody tr {
            opacity: 0.4;
            transition: opacity .15s;
        }

        /* Filtros pill-style */
        .filtros-grupo {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .filtro-btn {
            border: 2px solid #e0e7ff;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: .82rem;
            font-weight: 500;
            background: #f8f9ff;
            color: #6b7280;
            cursor: pointer;
            transition: all .2s;
            white-space: nowrap;
        }

        .filtro-btn:hover {
            border-color: #6366f1;
            color: #6366f1;
            background: #eef2ff;
        }

        .filtro-btn.activo {
            background: #6366f1;
            border-color: #6366f1;
            color: #fff;
        }

        .filtro-btn.activo-success {
            background: #22c55e;
            border-color: #22c55e;
            color: #fff;
        }

        .filtro-btn.activo-warning {
            background: #f59e0b;
            border-color: #f59e0b;
            color: #fff;
        }

        .search-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e0e7ff;
            color: #4f46e5;
            border-radius: 20px;
            padding: 2px 12px 2px 8px;
            font-size: .8rem;
            font-weight: 500;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="mb-0">Lista de Órdenes</h4>
                        <a href="{{ route('admin.ordenes.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Nueva Orden
                        </a>
                    </div>

                    {{-- Buscador --}}
                    <div class="search-wrapper mt-3">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" id="buscadorOrdenes" class="search-input"
                            placeholder="Buscar por código o cliente..." autocomplete="off" value="{{ $q ?? '' }}">
                        <div id="buscando-spinner">
                            <div class="spinner-ring"></div>
                        </div>
                        <button id="btnLimpiar" class="btn-clear-search {{ $q ?? '' ? '' : 'd-none' }}">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    {{-- Filtros --}}
                    <div class="mt-3 d-flex flex-wrap gap-4 align-items-center">
                        {{-- Período --}}
                        <div>
                            <small class="text-muted fw-semibold me-1"><i class="bi bi-calendar3"></i> Período:</small>
                            <div class="filtros-grupo d-inline-flex">
                                <button class="filtro-btn {{ $periodo === 'hoy' ? 'activo' : '' }}" data-filtro="periodo"
                                    data-valor="hoy">Hoy</button>
                                <button class="filtro-btn {{ $periodo === 'semana' ? 'activo' : '' }}" data-filtro="periodo"
                                    data-valor="semana">Esta semana</button>
                                <button class="filtro-btn {{ $periodo === 'todos' ? 'activo' : '' }}" data-filtro="periodo"
                                    data-valor="todos">Todos</button>
                            </div>
                        </div>
                        {{-- Pago --}}
                        <div>
                            <small class="text-muted fw-semibold me-1"><i class="bi bi-cash"></i> Pago:</small>
                            <div class="filtros-grupo d-inline-flex">
                                <button class="filtro-btn {{ $pago === 'pendiente' ? 'activo-warning' : '' }}"
                                    data-filtro="pago" data-valor="pendiente">Pendiente</button>
                                <button class="filtro-btn {{ $pago === 'pagado' ? 'activo-success' : '' }}"
                                    data-filtro="pago" data-valor="pagado">Pagado</button>
                                <button class="filtro-btn {{ $pago === 'todos' ? 'activo' : '' }}" data-filtro="pago"
                                    data-valor="todos">Todos</button>
                            </div>
                        </div>
                    </div>

                    <div id="search-info" class="mt-2">
                        @if ($q ?? false)
                            <span class="search-badge">
                                <i class="bi bi-funnel-fill"></i>
                                {{ $ordenes->total() }} resultado{{ $ordenes->total() !== 1 ? 's' : '' }} para
                                "{{ $q }}"
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-lg" id="tabla-resultados">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Cliente</th>
                                    <th>Equipo</th>
                                    <th>Técnico</th>
                                    <th>Estado</th>
                                    <th>Pago</th>
                                    <th>Fecha</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo-tabla">
                                @include('admin.ordenes._tabla_filas', ['ordenes' => $ordenes])
                            </tbody>
                        </table>

                        <div id="paginacion-wrapper">
                            @include('admin.ordenes._paginacion', ['ordenes' => $ordenes])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const input = document.getElementById('buscadorOrdenes');
        const btnLimpiar = document.getElementById('btnLimpiar');
        const spinner = document.getElementById('buscando-spinner');
        const cuerpo = document.getElementById('cuerpo-tabla');
        const tabla = document.getElementById('tabla-resultados');
        const pagWrapper = document.getElementById('paginacion-wrapper');
        const searchInfo = document.getElementById('search-info');
        const baseUrl = "{{ route('admin.ordenes.index') }}";

        let timer;
        let estado = {
            q: "{{ $q ?? '' }}",
            periodo: "{{ $periodo ?? 'todos' }}",
            pago: "{{ $pago ?? 'todos' }}",
        };

        // Resaltar filtros activos al cargar
        actualizarBotonesActivos();

        // Buscador
        input.addEventListener('input', function() {
            const q = this.value;
            estado.q = q;
            btnLimpiar.classList.toggle('d-none', q === '');
            clearTimeout(timer);
            timer = setTimeout(() => buscar(), 350);
        });

        btnLimpiar.addEventListener('click', function() {
            input.value = '';
            estado.q = '';
            this.classList.add('d-none');
            buscar();
            input.focus();
        });

        // Filtros
        document.querySelectorAll('.filtro-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const filtro = this.dataset.filtro;
                const valor = this.dataset.valor;
                estado[filtro] = valor;
                actualizarBotonesActivos();
                buscar();
            });
        });

        function actualizarBotonesActivos() {
            document.querySelectorAll('.filtro-btn').forEach(btn => {
                const filtro = btn.dataset.filtro;
                const valor = btn.dataset.valor;
                btn.classList.remove('activo', 'activo-success', 'activo-warning');
                if (estado[filtro] === valor) {
                    if (valor === 'pagado') btn.classList.add('activo-success');
                    else if (valor === 'pendiente') btn.classList.add('activo-warning');
                    else btn.classList.add('activo');
                }
            });
        }

        function buildUrl(base) {
            const params = new URLSearchParams();
            if (estado.q) params.set('q', estado.q);
            if (estado.periodo !== 'todos') params.set('periodo', estado.periodo);
            if (estado.pago !== 'todos') params.set('pago', estado.pago);
            const qs = params.toString();
            return qs ? `${base}?${qs}` : base;
        }

        function buscar(urlDirecta = null) {
            const url = urlDirecta ?? buildUrl(baseUrl);
            spinner.style.display = 'block';
            tabla.classList.add('cargando');

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    cuerpo.innerHTML = data.filas;
                    pagWrapper.innerHTML = data.paginacion;

                    if (estado.q && data.total > 0) {
                        searchInfo.innerHTML = `<span class="search-badge">
                            <i class="bi bi-funnel-fill"></i>
                            ${data.total} resultado${data.total !== 1 ? 's' : ''} para "${estado.q}"
                        </span>`;
                    } else {
                        searchInfo.innerHTML = '';
                    }

                    bindEliminar();
                    bindPaginacion();
                })
                .finally(() => {
                    spinner.style.display = 'none';
                    tabla.classList.remove('cargando');
                });
        }

        function bindEliminar() {
            document.querySelectorAll('.btn-eliminar').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: 'Se eliminará la orden permanentemente',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then(result => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        }

        function bindPaginacion() {
            document.querySelectorAll('#paginacion-wrapper a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Mantener filtros activos al paginar
                    const url = new URL(this.href);
                    if (estado.q) url.searchParams.set('q', estado.q);
                    if (estado.periodo !== 'todos') url.searchParams.set('periodo', estado.periodo);
                    if (estado.pago !== 'todos') url.searchParams.set('pago', estado.pago);
                    buscar(url.toString());
                });
            });
        }

        bindEliminar();
        bindPaginacion();
    </script>
@endsection
