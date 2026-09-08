@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Cambio de Estados</h3>
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
            width: 100%;
            transition: border-color .25s, box-shadow .25s, background .25s;
            outline: none;
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
                        <h4 class="mb-0">Órdenes activas</h4>
                    </div>

                    {{-- Buscador AJAX --}}
                    <div class="search-wrapper mt-3">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" id="buscadorEstados" class="search-input"
                            placeholder="Buscar por código, cliente o celular..." autocomplete="off"
                            value="{{ $q ?? '' }}">
                        <div id="buscando-spinner">
                            <div class="spinner-ring"></div>
                        </div>
                        <button id="btnLimpiar" class="btn-clear-search {{ $q ?? '' ? '' : 'd-none' }}"
                            title="Limpiar búsqueda">
                            <i class="bi bi-x-lg"></i>
                        </button>
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
                                    <th>Estado actual</th>
                                    <th class="text-center">Cambiar estado</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpo-tabla">
                                @include('admin.estados._tabla_filas', ['ordenes' => $ordenes])
                            </tbody>
                        </table>

                        <div id="paginacion-wrapper">
                            @include('admin.estados._paginacion', ['ordenes' => $ordenes])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CAMBIO DE ESTADO --}}
    <div class="modal fade" id="modalEstado" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Estado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEstado" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Orden: <strong id="modalCodigo"></strong></p>

                        <div class="form-group mb-3">
                            <label>Nuevo estado (*)</label>
                            <select name="estado" id="selectEstado" class="form-control">
                                <option value="recibido">📥 Recibido</option>
                                <option value="en_revision">🔍 En Revisión</option>
                                <option value="esperando_aprobacion">⏳ Esperando Aprobación</option>
                                <option value="en_reparacion">🔧 En Reparación</option>
                                <option value="listo">✅ Listo</option>
                                <option value="entregado">📦 Entregado</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Observación (opcional)</label>
                            <textarea name="observacion" class="form-control" rows="2"
                                placeholder="Ej: Se encontró problema adicional en la placa..."></textarea>
                        </div>

                        <div id="campoGarantia" class="form-group mb-3 d-none">
                            <label>Días de garantía</label>
                            <div class="input-group">
                                <input type="number" name="dias_garantia" class="form-control" value="30"
                                    min="0" max="365">
                                <span class="input-group-text">días</span>
                            </div>
                            <small class="text-muted">
                                Por defecto 30 días. Escribe 0 si no aplica garantía.
                            </small>
                        </div>

                        <div id="alertaEntregado" class="alert alert-warning d-none">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>¡Atención!</strong> Al marcar como <strong>Entregado</strong>:
                            <ul class="mb-0 mt-1">
                                <li>Se calculará el total final automáticamente</li>
                                <li>Se marcará como pagado</li>
                                <li>Se creará la garantía automáticamente</li>
                                <li>Esta acción no se puede deshacer fácilmente</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar cambio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const input = document.getElementById('buscadorEstados');
        const btnLimpiar = document.getElementById('btnLimpiar');
        const spinner = document.getElementById('buscando-spinner');
        const cuerpo = document.getElementById('cuerpo-tabla');
        const tabla = document.getElementById('tabla-resultados');
        const pagWrapper = document.getElementById('paginacion-wrapper');
        const searchInfo = document.getElementById('search-info');
        const baseUrl = "{{ route('admin.estados.index') }}";

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

        function buscar(q, urlDirecta = null) {
            if (!urlDirecta && q === ultimaBusqueda) return;
            ultimaBusqueda = q;

            const url = urlDirecta ?? (q ? `${baseUrl}?q=${encodeURIComponent(q)}` : baseUrl);

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

                    if (q && data.total > 0) {
                        searchInfo.innerHTML = `<span class="search-badge">
                            <i class="bi bi-funnel-fill"></i>
                            ${data.total} resultado${data.total !== 1 ? 's' : ''} para "${q}"
                        </span>`;
                    } else {
                        searchInfo.innerHTML = '';
                    }

                    bindPaginacion();
                })
                .finally(() => {
                    spinner.style.display = 'none';
                    tabla.classList.remove('cargando');
                    input.focus();
                    const len = input.value.length;
                    input.setSelectionRange(len, len);
                });
        }

        function bindPaginacion() {
            document.querySelectorAll('#paginacion-wrapper a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    if (ultimaBusqueda) url.searchParams.set('q', ultimaBusqueda);
                    buscar(ultimaBusqueda, url.toString());
                });
            });
        }

        bindPaginacion();

        // ── Modal ──────────────────────────────────────────────
        function abrirModal(id, codigo, estadoActual) {
            document.getElementById('formEstado').action = '{{ url('/admin/estados') }}/' + id;
            document.getElementById('modalCodigo').textContent = codigo;
            document.getElementById('selectEstado').value = estadoActual;

            document.getElementById('alertaEntregado').classList.add('d-none');
            document.getElementById('campoGarantia').classList.add('d-none');

            if (estadoActual === 'entregado') {
                document.getElementById('alertaEntregado').classList.remove('d-none');
                document.getElementById('campoGarantia').classList.remove('d-none');
            }

            new bootstrap.Modal(document.getElementById('modalEstado')).show();
        }

        document.getElementById('selectEstado').addEventListener('change', function() {
            const alerta = document.getElementById('alertaEntregado');
            const garantia = document.getElementById('campoGarantia');
            if (this.value === 'entregado') {
                alerta.classList.remove('d-none');
                garantia.classList.remove('d-none');
            } else {
                alerta.classList.add('d-none');
                garantia.classList.add('d-none');
            }
        });
    </script>
@endsection
