@extends('layouts.admin')

@section('content')

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <p class="text-muted mb-0" style="font-size:20px;">Bienvenido de vuelta,</p>
            <h5 class="fw-semibold mb-0">{{ Auth::user()->name }}</h5>
        </div>
        <span class="badge rounded-pill d-inline-flex align-items-center gap-1"
            style="background:#e8f0fe; color:#1a56db; border:1px solid #c3d5fb; font-size:12px; padding:6px 13px; font-weight:500;">
            <i class="bi bi-person-fill" style="font-size:12px;"></i>
            {{ Auth::user()->roles->pluck('name')->implode(', ') }}
        </span>
    </div>
    <hr class="mt-0 mb-4">

    {{-- TARJETAS RESUMEN --}}
    <div class="row g-3 mb-4">

        @can('dashboard.ordenes')
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('admin.ordenes.index') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm dashboard-card" style="border-top:4px solid #0d6efd !important;">
                        <div class="card-body py-3 text-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                style="width:44px;height:44px;background:rgba(13,110,253,.1);">
                                <i class="bi bi-clipboard-plus text-primary fs-5"></i>
                            </div>
                            <h3 class="fw-bold text-primary mb-0">{{ $resumen['ordenes_hoy'] }}</h3>
                            <small class="text-muted">Órdenes hoy</small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('admin.ordenes.index', ['estado' => 'en_reparacion']) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm dashboard-card" style="border-top:4px solid #ffc107 !important;">
                        <div class="card-body py-3 text-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                style="width:44px;height:44px;background:rgba(255,193,7,.1);">
                                <i class="bi bi-tools text-warning fs-5"></i>
                            </div>
                            <h3 class="fw-bold text-warning mb-0">{{ $resumen['en_proceso'] }}</h3>
                            <small class="text-muted">En proceso</small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('admin.ordenes.index', ['estado' => 'entregado']) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm dashboard-card" style="border-top:4px solid #198754 !important;">
                        <div class="card-body py-3 text-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                style="width:44px;height:44px;background:rgba(25,135,84,.1);">
                                <i class="bi bi-check-circle text-success fs-5"></i>
                            </div>
                            <h3 class="fw-bold text-success mb-0">{{ $resumen['entregadas_mes'] }}</h3>
                            <small class="text-muted">Entregadas este mes</small>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('dashboard.ingresos')
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('admin.ingresos.index') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm dashboard-card" style="border-top:4px solid #20c997 !important;">
                        <div class="card-body py-3 text-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                style="width:44px;height:44px;background:rgba(32,201,151,.1);">
                                <i class="bi bi-cash-coin fs-5" style="color:#20c997;"></i>
                            </div>
                            <h5 class="fw-bold mb-0" style="color:#20c997;">
                                S/. {{ number_format($resumen['ingresos_mes'], 2) }}
                            </h5>
                            <small class="text-muted">Ingresos este mes</small>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('dashboard.ordenes')
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('admin.ordenes.index', ['estado' => 'entregado']) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm dashboard-card" style="border-top:4px solid #0dcaf0 !important;">
                        <div class="card-body py-3 text-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                style="width:44px;height:44px;background:rgba(13,202,240,.1);">
                                <i class="bi bi-shield-check text-info fs-5"></i>
                            </div>
                            <h3 class="fw-bold text-info mb-0">{{ $resumen['garantias_vigentes'] }}</h3>
                            <small class="text-muted">Garantías vigentes</small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('admin.clientes.index') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm dashboard-card" style="border-top:4px solid #6c757d !important;">
                        <div class="card-body py-3 text-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                style="width:44px;height:44px;background:rgba(108,117,125,.1);">
                                <i class="bi bi-people text-secondary fs-5"></i>
                            </div>
                            <h3 class="fw-bold text-secondary mb-0">{{ $resumen['total_clientes'] }}</h3>
                            <small class="text-muted">Clientes registrados</small>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

    </div>

    <style>
        .dashboard-card {
            transition: transform .18s ease, box-shadow .18s ease;
            cursor: pointer;
        }

        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .1) !important;
        }

        .btn-periodo.activo {
            background-color: #F07820 !important;
            border-color: #F07820 !important;
            color: #fff !important;
        }
    </style>

    {{-- FILA 1: Ingresos 6 meses + Órdenes por estado --}}
    @canany(['dashboard.ingresos', 'dashboard.ordenes'])
        <div class="row g-3 mb-4">

            @can('dashboard.ingresos')
                {{-- Ingresos últimos 6 meses --}}
                <div class="col-md-8">
                    <div class="card h-100">
                        <div class="card-header">
                            <h4>Ingresos — Últimos 6 meses</h4>
                        </div>
                        <div class="card-body">
                            <div id="graficoIngresos"></div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('dashboard.ordenes')
                {{-- Órdenes por estado --}}
                <div class="{{ auth()->user()->can('dashboard.ingresos') ? 'col-md-4' : 'col-md-12' }}">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Órdenes por Estado</h4>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-secondary btn-periodo btn-periodo-estado activo"
                                    data-periodo="semana">Semana</button>
                                <button class="btn btn-outline-secondary btn-periodo btn-periodo-estado"
                                    data-periodo="hoy">Hoy</button>
                                <button class="btn btn-outline-secondary btn-periodo btn-periodo-estado"
                                    data-periodo="mes">Mes</button>
                            </div>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div id="graficoPorEstado" style="width:100%;"></div>
                        </div>
                    </div>
                </div>
            @endcan

        </div>
    @endcanany

    {{-- FILA 2: Actividad 7 días + Top servicios --}}
    @can('dashboard.graficos')
        <div class="row g-3 mb-4">

            {{-- Órdenes con selector de período --}}
            <div class="col-md-7">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Actividad</h4>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-secondary btn-periodo btn-periodo-actividad activo"
                                data-periodo="semana">Semana</button>
                            <button class="btn btn-outline-secondary btn-periodo btn-periodo-actividad"
                                data-periodo="hoy">Hoy</button>
                            <button class="btn btn-outline-secondary btn-periodo btn-periodo-actividad"
                                data-periodo="mes">Mes</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="grafico7Dias"></div>
                    </div>
                </div>
            </div>

            {{-- Top servicios con selector de período --}}
            <div class="col-md-5">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Top 5 Servicios</h4>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-secondary btn-periodo btn-periodo-servicios activo"
                                data-periodo="semana">Semana</button>
                            <button class="btn btn-outline-secondary btn-periodo btn-periodo-servicios"
                                data-periodo="hoy">Hoy</button>
                            <button class="btn btn-outline-secondary btn-periodo btn-periodo-servicios"
                                data-periodo="mes">Mes</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="graficoTopServicios"></div>
                    </div>
                </div>
            </div>

        </div>
    @endcan

    {{-- FILA 3: Últimas órdenes + Técnicos del mes --}}
    @canany(['dashboard.ordenes', 'dashboard.tecnicos'])
        <div class="row g-3">

            @can('dashboard.ordenes')
                {{-- Últimas órdenes --}}
                <div class="{{ auth()->user()->can('dashboard.tecnicos') ? 'col-md-8' : 'col-md-12' }}">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="bi bi-clock-history me-2"></i>Últimas Órdenes
                            </h4>
                            <a href="{{ route('admin.ordenes.index') }}" class="btn btn-outline-primary btn-sm">
                                Ver todas <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-3">Código</th>
                                            <th>Cliente</th>
                                            <th>Equipo</th>
                                            <th>Técnico</th>
                                            <th class="pe-3">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ultimasOrdenes as $orden)
                                            <tr style="cursor:pointer;"
                                                onclick="window.location='{{ route('admin.ordenes.show', $orden->id) }}'">
                                                <td class="ps-3">
                                                    <a href="{{ route('admin.ordenes.show', $orden->id) }}"
                                                        class="fw-bold text-decoration-none" onclick="event.stopPropagation()">
                                                        {{ $orden->codigo }}
                                                    </a>
                                                </td>
                                                <td>{{ $orden->cliente->nombre }}</td>
                                                <td>{{ $orden->tipoEquipo->nombre }}</td>
                                                <td>{{ $orden->tecnico->name ?? '—' }}</td>
                                                <td class="pe-3">{!! $orden->badgeEstado() !!}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('dashboard.tecnicos')
                {{-- Técnicos del mes --}}
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 card-dark">
                        <div class="card-header border-0">
                            <h4 class="mb-0 fw-semibold">Técnicos — Este mes</h4>
                        </div>

                        <div class="card-body">

                            @php
                                $maxOrdenes = $tecnicosStats->max('total_entregadas') ?: 1;
                            @endphp

                            @forelse($tecnicosStats as $index => $tecnico)
                                @php
                                    $pct = ($tecnico->total_entregadas / $maxOrdenes) * 100;
                                    $isTop = $index === 0;
                                @endphp

                                <div class="mb-4">

                                    {{-- Nombre y total --}}
                                    <div class="d-flex justify-content-between mb-1 align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                style="width:32px;height:32px; background:var(--circle-bg);">
                                                <i class="bi bi-person-fill text-primary"></i>
                                            </div>
                                            <span class="fw-semibold text-truncate" style="max-width:150px;">
                                                {{ $tecnico->name }}
                                            </span>

                                            @if ($isTop)
                                                <span class="badge bg-warning text-dark">Top</span>
                                            @endif
                                        </div>

                                        <span class="fw-bold">
                                            {{ $tecnico->total_entregadas }}
                                        </span>
                                    </div>

                                    {{-- Barra moderna --}}
                                    <div class="progress" style="height:10px; background:var(--progress-bg); border-radius:20px;">
                                        <div class="progress-bar"
                                            style="width: {{ $pct }}%;
                                    background: linear-gradient(90deg, #4F8CFF, #22C55E);
                                    border-radius:20px;">
                                        </div>
                                    </div>

                                </div>
                            @empty
                                <p class="text-muted text-center mb-0">
                                    Sin técnicos registrados
                                </p>
                            @endforelse

                        </div>
                    </div>
                </div>
            @endcan

        </div>
    @endcanany

    {{-- SCRIPTS DE GRÁFICOS --}}
    @push('scripts')
        <script>
            @can('dashboard.ingresos')
                // ── 1. INGRESOS 6 MESES ──────────────────────────────
                new ApexCharts(document.getElementById('graficoIngresos'), {
                    chart: {
                        type: 'area',
                        height: 280,
                        toolbar: {
                            show: false
                        }
                    },
                    series: [{
                        name: 'Ingresos (S/.)',
                        data: {!! json_encode(array_column($ingresosMeses, 'total')) !!}
                    }],
                    xaxis: {
                        categories: {!! json_encode(array_column($ingresosMeses, 'mes')) !!}
                    },
                    colors: ['#4F8CFF'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            opacityFrom: 0.5,
                            opacityTo: 0.1
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    dataLabels: {
                        enabled: false
                    },
                    tooltip: {
                        y: {
                            formatter: val => 'S/. ' + val.toFixed(2)
                        }
                    },
                    grid: {
                        borderColor: '#f0f0f0'
                    },
                }).render();
            @endcan

            @can('dashboard.ordenes')
                // ── 2. ÓRDENES POR ESTADO con selector ──────────────
                const estadoNombres = {
                    recibido: 'Recibido',
                    en_revision: 'En Revisión',
                    esperando_aprobacion: 'Esp. Aprobación',
                    en_reparacion: 'En Reparación',
                    listo: 'Listo',
                    entregado: 'Entregado',
                };

                let chartEstado = null;

                function renderEstado(datos) {
                    if (chartEstado) chartEstado.destroy();
                    const valores = Object.values(datos);
                    const totalOrdenes = valores.reduce((a, b) => a + b, 0);
                    chartEstado = new ApexCharts(document.getElementById('graficoPorEstado'), {
                        chart: {
                            type: 'donut',
                            height: 310
                        },
                        series: valores,
                        labels: Object.keys(datos).map(k => estadoNombres[k] || k),
                        colors: ['#4F8CFF', '#22C55E', '#FACC15', '#A855F7', '#FB923C', '#06B6D4'],
                        legend: {
                            position: 'bottom',
                            fontSize: '13px',
                            labels: {
                                colors: '#6B7280'
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        tooltip: {
                            y: {
                                formatter: val => val + " órdenes"
                            }
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '72%',
                                    labels: {
                                        show: true,
                                        name: {
                                            show: true,
                                            fontSize: '14px',
                                            color: '#6B7280'
                                        },
                                        value: {
                                            show: true,
                                            fontSize: '20px',
                                            fontWeight: 700,
                                            color: '#111827'
                                        },
                                        total: {
                                            show: true,
                                            label: 'Total',
                                            fontSize: '14px',
                                            fontWeight: 600,
                                            color: '#6B7280',
                                            formatter: () => totalOrdenes
                                        }
                                    }
                                }
                            }
                        }
                    });
                    chartEstado.render();
                }

                renderEstado(@json($porEstado));

                document.querySelectorAll('.btn-periodo-estado').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.btn-periodo-estado').forEach(b => b.classList.remove(
                            'activo'));
                        this.classList.add('activo');
                        fetch(`{{ url('/admin/dashboard/ordenes-estado') }}?periodo=${this.dataset.periodo}`)
                            .then(r => r.json()).then(datos => renderEstado(datos));
                    });
                });
            @endcan

            @can('dashboard.graficos')
                // ── 3. ACTIVIDAD con selector ────────────────────────
                let chartActividad = null;

                function renderActividad(datos) {
                    if (chartActividad) chartActividad.destroy();
                    chartActividad = new ApexCharts(document.getElementById('grafico7Dias'), {
                        chart: {
                            type: 'bar',
                            height: 280,
                            toolbar: {
                                show: false
                            },
                            background: 'transparent'
                        },
                        series: [{
                                name: 'Órdenes',
                                type: 'bar',
                                data: datos.map(d => d.total)
                            },
                            {
                                name: 'Ingresos',
                                type: 'line',
                                data: datos.map(d => d.ingresos)
                            }
                        ],
                        xaxis: {
                            categories: datos.map(d => d.dia),
                            labels: {
                                style: {
                                    colors: '#6c757d',
                                    fontSize: '13px'
                                }
                            }
                        },
                        yaxis: [{
                                title: {
                                    text: 'Órdenes'
                                },
                                labels: {
                                    style: {
                                        colors: '#22C55E'
                                    }
                                }
                            },
                            {
                                opposite: true,
                                title: {
                                    text: 'Ingresos (S/.)'
                                },
                                labels: {
                                    style: {
                                        colors: '#3b82f6'
                                    },
                                    formatter: val => 'S/. ' + val.toFixed(2)
                                }
                            }
                        ],
                        colors: ['#22C55E', '#3b82f6'],
                        fill: {
                            type: ['gradient', 'solid'],
                            gradient: {
                                shade: 'light',
                                type: 'vertical',
                                shadeIntensity: 0.4,
                                gradientToColors: ['#22C55E'],
                                inverseColors: false,
                                opacityFrom: 0.9,
                                opacityTo: 0.7,
                                stops: [0, 100]
                            }
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                columnWidth: '45%'
                            }
                        },
                        stroke: {
                            width: [0, 4],
                            curve: 'smooth'
                        },
                        dataLabels: {
                            enabled: false
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            theme: 'light',
                            style: {
                                fontSize: '13px'
                            },
                            y: [{
                                formatter: val => val + ' órdenes'
                            }, {
                                formatter: val => 'S/. ' + parseFloat(val).toFixed(2)
                            }]
                        },
                        legend: {
                            position: 'top',
                            labels: {
                                colors: '#343a40'
                            }
                        },
                        grid: {
                            borderColor: '#e9ecef',
                            strokeDashArray: 4
                        }
                    });
                    chartActividad.render();
                }
                renderActividad(@json($ordenesDias));

                document.querySelectorAll('.btn-periodo-actividad').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.btn-periodo-actividad').forEach(b => b.classList.remove(
                            'activo'));
                        this.classList.add('activo');
                        fetch(`{{ url('/admin/dashboard/actividad') }}?periodo=${this.dataset.periodo}`)
                            .then(r => r.json()).then(datos => renderActividad(datos));
                    });
                });

                // ── 4. TOP SERVICIOS con selector ────────────────────
                let chartServicios = null;

                function renderServicios(datos) {
                    if (chartServicios) chartServicios.destroy();
                    chartServicios = new ApexCharts(document.getElementById('graficoTopServicios'), {
                        chart: {
                            type: 'bar',
                            height: 260,
                            toolbar: {
                                show: false
                            }
                        },
                        series: [{
                            name: 'Veces realizado',
                            data: datos.map(s => s.total)
                        }],
                        xaxis: {
                            categories: datos.map(s => s.nombre)
                        },
                        colors: ['#22C55E'],
                        plotOptions: {
                            bar: {
                                borderRadius: 6,
                                horizontal: true,
                                barHeight: '55%'
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        grid: {
                            borderColor: '#f0f0f0'
                        },
                    });
                    chartServicios.render();
                }
                renderServicios(@json($topServicios));

                document.querySelectorAll('.btn-periodo-servicios').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.btn-periodo-servicios').forEach(b => b.classList.remove(
                            'activo'));
                        this.classList.add('activo');
                        fetch(`{{ url('/admin/dashboard/top-servicios') }}?periodo=${this.dataset.periodo}`)
                            .then(r => r.json()).then(datos => renderServicios(datos));
                    });
                });
            @endcan
        </script>
    @endpush
@endsection
