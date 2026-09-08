@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Garantías</h3>
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
            color: #F07820;
            font-size: 1rem;
            pointer-events: none;
        }

        .search-input {
            padding-left: 42px;
            padding-right: 40px;
            height: 42px;
            border: 2px solid #ffe0c8;
            border-radius: 12px;
            font-size: 0.92rem;
            background: #fff8f4;
            transition: border-color 0.25s, box-shadow 0.25s, background 0.25s;
            outline: none;
        }

        .search-input:focus {
            border-color: #F07820;
            box-shadow: 0 0 0 4px rgba(240, 120, 32, 0.12);
            background: #fff;
        }

        .search-input::placeholder {
            color: #ffb380;
        }

        .btn-clear-search {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #ffb380;
            font-size: 0.85rem;
            cursor: pointer;
            padding: 2px 4px;
            border-radius: 6px;
            transition: color 0.2s, background 0.2s;
        }

        .btn-clear-search:hover {
            color: #F07820;
            background: #ffe0c8;
        }

        .search-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ffe0c8;
            color: #c05a10;
            border-radius: 20px;
            padding: 2px 12px 2px 8px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 6px;
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
            border: 2px solid #ffe0c8;
            border-top-color: #F07820;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        #tabla-garantias.cargando tbody tr {
            opacity: 0.4;
            transition: opacity 0.15s;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="mb-0">Registro de Garantías</h4>
                    </div>

                    {{-- BUSCADOR --}}
                    <div class="search-wrapper mt-3">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" id="buscadorGarantias" class="form-control search-input"
                            placeholder="Buscar por cliente, código o equipo..." autocomplete="off"
                            value="{{ $q ?? '' }}">
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
                                {{ $garantias->total() }} resultado{{ $garantias->total() !== 1 ? 's' : '' }} para
                                "{{ $q }}"
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="tabla-garantias">
                            <thead>
                                <tr>
                                    <th>Orden</th>
                                    <th>Cliente</th>
                                    <th>Equipo</th>
                                    <th>Fecha inicio</th>
                                    <th>Fecha vence</th>
                                    <th>Días restantes</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo-tabla">
                                @include('admin.garantias._tabla_filas', ['garantias' => $garantias])
                            </tbody>
                        </table>

                        <div id="sin-resultados"
                            class="{{ $garantias->isEmpty() ? '' : 'd-none' }} text-center text-muted py-4">
                            <i class="bi bi-shield-x fs-3 d-block mb-2"></i>
                            No se encontraron garantías con ese criterio.
                        </div>

                        <div id="paginacion-wrapper">
                            @include('admin.garantias._paginacion', ['garantias' => $garantias])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const input = document.getElementById('buscadorGarantias');
        const btnLimpiar = document.getElementById('btnLimpiar');
        const spinner = document.getElementById('buscando-spinner');
        const cuerpo = document.getElementById('cuerpo-tabla');
        const tabla = document.getElementById('tabla-garantias');
        const sinRes = document.getElementById('sin-resultados');
        const pagWrapper = document.getElementById('paginacion-wrapper');
        const searchInfo = document.getElementById('search-info');
        const baseUrl = "{{ route('admin.garantias.index') }}";

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
                        ${data.total} resultado${data.total !== 1 ? 's' : ''} para "${q}"
                    </span>`;
                    } else {
                        searchInfo.innerHTML = '';
                    }

                    bindAcciones();
                    bindPaginacion();
                })
                .finally(() => {
                    spinner.style.display = 'none';
                    tabla.classList.remove('cargando');
                    const len = input.value.length;
                    input.focus();
                    input.setSelectionRange(len, len);
                });
        }

        function bindAcciones() {
            document.querySelectorAll('.btn-marcar-usada').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    Swal.fire({
                        title: '¿Marcar garantía como usada?',
                        text: 'El cliente está haciendo uso de su garantía',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f39c12',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, marcar usada',
                        cancelButtonText: 'Cancelar'
                    }).then(result => {
                        if (result.isConfirmed) {
                            document.getElementById('form_usar_' + id).submit();
                        }
                    });
                });
            });
        }

        function bindPaginacion() {
            document.querySelectorAll('#paginacion-wrapper a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    spinner.style.display = 'block';
                    tabla.classList.add('cargando');
                    fetch(this.href, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            cuerpo.innerHTML = data.filas;
                            pagWrapper.innerHTML = data.paginacion;
                            sinRes.classList.toggle('d-none', data.total > 0);
                            bindAcciones();
                            bindPaginacion();
                        })
                        .finally(() => {
                            spinner.style.display = 'none';
                            tabla.classList.remove('cargando');
                        });
                });
            });
        }

        bindAcciones();
        bindPaginacion();
    </script>
@endsection
