<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Soporte Tecnico</title>
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/nexos-theme.css') }}">
</head>

<body>
    <script src="{{ asset('/assets/static/js/initTheme.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="{{ url('/admin') }}">
                                <img src="{{ asset('assets/compiled/imagen/image.png') }}" alt="Logo"
                                    style="width:200px; height:auto;">
                            </a>
                        </div>
                        <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                class="iconify iconify--system-uicons" width="20" height="20"
                                viewBox="0 0 21 21">
                                <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                        opacity=".3"></path>
                                    <g transform="translate(-210 -1)">
                                        <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                        <circle cx="220.5" cy="11.5" r="4"></circle>
                                        <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2">
                                        </path>
                                    </g>
                                </g>
                            </svg>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input me-0" type="checkbox" id="toggle-dark"
                                    style="cursor:pointer">
                                <label class="form-check-label"></label>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                class="iconify iconify--mdi" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z" />
                            </svg>
                        </div>
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block">
                                <i class="bi bi-x bi-middle"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        {{-- INICIO — siempre visible --}}
                        <li class="sidebar-item {{ request()->is('admin') ? 'active' : '' }}">
                            <a href="{{ url('/admin') }}" class="sidebar-link">
                                <i class="bi bi-house"></i>
                                <span>Inicio</span>
                            </a>
                        </li>

                        {{-- ÓRDENES --}}
                        @canany(['ordenes.ver', 'ordenes.crear'])
                            <li class="sidebar-item has-sub {{ request()->is('admin/ordenes*') ? 'active' : '' }}">
                                <a href="#" class="sidebar-link">
                                    <i class="bi bi-tools"></i>
                                    <span>Órdenes</span>
                                </a>
                                <ul class="submenu {{ request()->is('admin/ordenes*') ? 'submenu-open' : '' }}">
                                    @can('ordenes.crear')
                                        <li class="submenu-item {{ request()->is('admin/ordenes/crear') ? 'active' : '' }}">
                                            <a href="{{ url('/admin/ordenes/crear') }}" class="submenu-link">Nueva Orden</a>
                                        </li>
                                    @endcan
                                    @can('ordenes.ver')
                                        <li class="submenu-item {{ request()->is('admin/ordenes') ? 'active' : '' }}">
                                            <a href="{{ url('/admin/ordenes') }}" class="submenu-link">Lista de Órdenes</a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        {{-- ESTADOS --}}
                        @can('estados.ver')
                            <li class="sidebar-item {{ request()->is('admin/estados*') ? 'active' : '' }}">
                                <a href="{{ url('/admin/estados') }}" class="sidebar-link">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <span>Cambio de Estados</span>
                                </a>
                            </li>
                        @endcan

                        {{-- CLIENTES --}}
                        @can('clientes.ver')
                            <li class="sidebar-item {{ request()->is('admin/clientes*') ? 'active' : '' }}">
                                <a href="{{ url('/admin/clientes') }}" class="sidebar-link">
                                    <i class="bi bi-people-fill"></i>
                                    <span>Clientes</span>
                                </a>
                            </li>
                        @endcan

                        {{-- GARANTÍAS --}}
                        @can('garantias.ver')
                            <li class="sidebar-item {{ request()->is('admin/garantias*') ? 'active' : '' }}">
                                <a href="{{ url('/admin/garantias') }}" class="sidebar-link">
                                    <i class="bi bi-shield-fill-check"></i>
                                    <span>Garantías</span>
                                </a>
                            </li>
                        @endcan

                        {{-- INGRESOS --}}
                        @can('ingresos.ver')
                            <li class="sidebar-item {{ request()->is('admin/ingresos*') ? 'active' : '' }}">
                                <a href="{{ url('/admin/ingresos') }}" class="sidebar-link">
                                    <i class="bi bi-cash-coin"></i>
                                    <span>Ingresos</span>
                                </a>
                            </li>
                        @endcan

                        {{-- REPORTES --}}
                        @can('reportes.ver')
                            <li class="sidebar-item {{ request()->is('admin/reportes*') ? 'active' : '' }}">
                                <a href="{{ url('/admin/reportes') }}" class="sidebar-link">
                                    <i class="bi bi-bar-chart-fill"></i>
                                    <span>Reportes</span>
                                </a>
                            </li>
                        @endcan

                        {{-- CONFIGURACIÓN --}}
                        @canany(['usuarios.ver', 'roles.ver', 'tipos_equipo.ver', 'servicios.ver'])
                            <li
                                class="sidebar-item has-sub
                            {{ request()->is('admin/usuarios*') ||
                            request()->is('admin/roles*') ||
                            request()->is('admin/tipos-equipo*') ||
                            request()->is('admin/servicios*')
                                ? 'active'
                                : '' }}">
                                <a href="#" class="sidebar-link">
                                    <i class="bi bi-gear-fill"></i>
                                    <span>Configuración</span>
                                </a>
                                <ul
                                    class="submenu
                                {{ request()->is('admin/usuarios*') ||
                                request()->is('admin/roles*') ||
                                request()->is('admin/tipos-equipo*') ||
                                request()->is('admin/servicios*')
                                    ? 'submenu-open'
                                    : '' }}">

                                    @can('usuarios.ver')
                                        <li class="submenu-item {{ request()->is('admin/usuarios*') ? 'active' : '' }}">
                                            <a href="{{ url('/admin/usuarios') }}" class="submenu-link">
                                                <i class="bi bi-person-add"></i> Usuarios
                                            </a>
                                        </li>
                                    @endcan

                                    @can('roles.ver')
                                        <li class="submenu-item {{ request()->is('admin/roles*') ? 'active' : '' }}">
                                            <a href="{{ url('/admin/roles') }}" class="submenu-link">Roles</a>
                                        </li>
                                    @endcan

                                    @can('tipos_equipo.ver')
                                        <li class="submenu-item {{ request()->is('admin/tipos-equipo*') ? 'active' : '' }}">
                                            <a href="{{ url('/admin/tipos-equipo') }}" class="submenu-link">
                                                <i class="bi bi-laptop"></i> Tipos de Equipo
                                            </a>
                                        </li>
                                    @endcan

                                    @can('servicios.ver')
                                        <li class="submenu-item {{ request()->is('admin/servicios*') ? 'active' : '' }}">
                                            <a href="{{ url('/admin/servicios') }}" class="submenu-link">
                                                Catálogo de Servicios
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        {{-- AJUSTES — siempre visible --}}
                        <li class="sidebar-title">Ajustes</li>
                        <li class="sidebar-item has-sub">
                            <a href="#" class="sidebar-link">
                                <i class="bi bi-person-circle"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item">
                                    <a href="{{ route('admin.perfil.index') }}" class="submenu-link">
                                        Perfil - Seguridad
                                    </a>
                                </li>
                                <li class="submenu-item">
                                    <a href="{{ route('logout') }}" class="submenu-link"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Cerrar Sesión
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display:none;">
                                        @csrf
                                        @method('POST')
                                    </form>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            <div class="page-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ url('/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ url('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ url('assets/compiled/js/app.js') }}"></script>
    <script src="{{ url('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>

    @stack('scripts')

    @if (($mensaje = Session::get('mensaje')) && ($icono = Session::get('icono')))
        <script>
            Swal.fire({
                icon: "{{ $icono }}",
                title: "{{ $mensaje }}",
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @endif
    @if (Session::get('abrir_ticket'))
        <script>
            setTimeout(function() {
                window.open('{{ Session::get('abrir_ticket') }}', '_blank');
            }, 1600);
        </script>
    @endif

</body>

</html>
