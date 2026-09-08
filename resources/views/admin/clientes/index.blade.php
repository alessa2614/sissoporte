@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Clientes</h3>
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
            transition: color 0.2s;
        }

        .search-input {
            padding-left: 42px;
            padding-right: 40px;
            height: 42px;
            border: 2px solid #e0e7ff;
            border-radius: 12px;
            font-size: 0.92rem;
            background: #f8f9ff;
            transition: border-color 0.25s, box-shadow 0.25s, background 0.25s;
            outline: none;
        }

        .search-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
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
            font-size: 0.85rem;
            cursor: pointer;
            padding: 2px 4px;
            border-radius: 6px;
            transition: color 0.2s, background 0.2s;
        }

        .btn-clear-search:hover {
            color: #6366f1;
            background: #e0e7ff;
        }

        .search-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e0e7ff;
            color: #4f46e5;
            border-radius: 20px;
            padding: 2px 12px 2px 8px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 6px;
        }

        .search-badge i {
            font-size: 0.75rem;
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
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        #tabla-resultados tbody tr {
            transition: opacity 0.15s;
        }

        #tabla-resultados.cargando tbody tr {
            opacity: 0.4;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="mb-0">Clientes registrados</h4>
                        <a href="{{ route('admin.clientes.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Nuevo Cliente
                        </a>
                    </div>

                    {{-- Buscador AJAX --}}
                    <div class="search-wrapper mt-3">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" id="buscadorClientes" class="form-control search-input"
                            placeholder="Buscar por nombre o celular..." autocomplete="off" value="{{ $q ?? '' }}">
                        <div id="buscando-spinner">
                            <div class="spinner-ring"></div>
                        </div>
                        <button id="btnLimpiar" class="btn-clear-search {{ $q ?? false ? '' : 'd-none' }}"
                            title="Limpiar búsqueda">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <div id="search-info" class="mt-1">
                        @if ($q ?? false)
                            <span class="search-badge">
                                <i class="bi bi-funnel-fill"></i>
                                <span id="resultado-texto">{{ $clientes->total() }}
                                    resultado{{ $clientes->total() !== 1 ? 's' : '' }} para "{{ $q }}"</span>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-lg" id="tabla-resultados">
                            <thead>
                                <tr>
                                    <th>Nro</th>
                                    <th>Nombre</th>
                                    <th>Celular</th>
                                    <th>Correo</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo-tabla">
                                @include('admin.clientes._tabla_filas', ['clientes' => $clientes])
                            </tbody>
                        </table>

                        <div id="sin-resultados"
                            class="{{ $clientes->isEmpty() ? '' : 'd-none' }} text-center text-muted py-4">
                            <i class="bi bi-search fs-3 d-block mb-2"></i>
                            No se encontraron clientes con ese criterio.
                        </div>

                        <div id="paginacion-wrapper">
                            @include('admin.clientes._paginacion', ['clientes' => $clientes])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const input = document.getElementById('buscadorClientes');
        const btnLimpiar = document.getElementById('btnLimpiar');
        const spinner = document.getElementById('buscando-spinner');
        const cuerpo = document.getElementById('cuerpo-tabla');
        const tabla = document.getElementById('tabla-resultados');
        const sinRes = document.getElementById('sin-resultados');
        const pagWrapper = document.getElementById('paginacion-wrapper');
        const searchInfo = document.getElementById('search-info');
        const baseUrl = "{{ route('admin.clientes.index') }}";

        let timer;
        let ultimaBusqueda = "{{ $q ?? '' }}";

        input.addEventListener('input', function() {
            const q = this.value;
            btnLimpiar.classList.toggle('d-none', q === '');
            clearTimeout(timer);
            timer = setTimeout(() => buscar(q), 350);
        });

        btnLimpiar.addEventListener('click', function() {
            input.value = '';
            this.classList.add('d-none');
            buscar('');
            input.focus();
        });

        function buscar(q) {
            if (q === ultimaBusqueda) return;
            ultimaBusqueda = q;

            spinner.style.display = 'block';
            tabla.classList.add('cargando');

            fetch(`${baseUrl}?q=${encodeURIComponent(q)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    cuerpo.innerHTML = data.filas;
                    pagWrapper.innerHTML = data.paginacion;
                    sinRes.classList.toggle('d-none', data.total > 0);

                    if (q && data.total > 0) {
                        searchInfo.innerHTML = `<span class="search-badge">
                        <i class="bi bi-funnel-fill"></i>
                        <span>${data.total} resultado${data.total !== 1 ? 's' : ''} para "${q}"</span>
                    </span>`;
                    } else {
                        searchInfo.innerHTML = '';
                    }

                    // Rebindear botones eliminar
                    bindEliminar();
                    // Rebindear paginación AJAX
                    bindPaginacion();
                })
                .finally(() => {
                    spinner.style.display = 'none';
                    tabla.classList.remove('cargando');
                    input.focus(); // <-- mantener foco siempre
                    // Mover cursor al final
                    const len = input.value.length;
                    input.setSelectionRange(len, len);
                });
        }

        function bindEliminar() {
            document.querySelectorAll('.btn-eliminar').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: 'Esta acción no se puede deshacer',
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
                    const url = this.href;
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
                            sinRes.classList.toggle('d-none', data.total > 0);
                            bindEliminar();
                            bindPaginacion();
                        })
                        .finally(() => {
                            spinner.style.display = 'none';
                            tabla.classList.remove('cargando');
                        });
                });
            });
        }

        // Init
        bindEliminar();
        bindPaginacion();
    </script>
@endsection
