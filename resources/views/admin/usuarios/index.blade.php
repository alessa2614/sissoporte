@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Usuarios</h3>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Usuarios registrados</h4>
                    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Nuevo Usuario
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-lg">
                            <thead>
                                <tr>
                                    <th>Nro</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Rol</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $nro = ($usuarios->currentPage() - 1) * $usuarios->perPage() + 1; @endphp

                                @foreach ($usuarios as $usuario)
                                    <tr class="{{ !$usuario->estado ? 'opacity-50' : '' }}">
                                        <td>{{ $nro++ }}</td>
                                        <td>
                                            {{ $usuario->name }}
                                            @if (!$usuario->estado)
                                                <small class="text-muted">(inactivo)</small>
                                            @endif
                                        </td>
                                        <td>{{ $usuario->email }}</td>

                                        {{-- ROL --}}
                                        <td>
                                            @php $rol = $usuario->getRoleNames()->first() @endphp
                                            @if ($rol == 'ADMIN')
                                                <span class="badge bg-danger">{{ $rol }}</span>
                                            @elseif ($rol == 'TECNICO')
                                                <span class="badge bg-primary">{{ $rol }}</span>
                                            @elseif ($rol)
                                                <span class="badge bg-success">{{ $rol }}</span>
                                            @else
                                                <span class="badge bg-secondary">Sin rol</span>
                                            @endif
                                        </td>

                                        {{-- ESTADO --}}
                                        <td class="text-center">
                                            @if ($usuario->estado)
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-danger">Inactivo</span>
                                            @endif
                                        </td>

                                        {{-- ACCIONES --}}
                                        <td class="text-center">
                                            <a href="{{ route('admin.usuarios.edit', $usuario->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>

                                            {{-- Toggle estado en vez de eliminar --}}
                                            @if ($usuario->id !== Auth::id())
                                                <form action="{{ route('admin.usuarios.toggle', $usuario->id) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if ($usuario->estado)
                                                        <button type="submit" class="btn btn-sm btn-warning btn-toggle"
                                                            data-nombre="{{ $usuario->name }}" data-accion="desactivar">
                                                            <i class="bi bi-person-slash"></i> Desactivar
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-sm btn-success btn-toggle"
                                                            data-nombre="{{ $usuario->name }}" data-accion="activar">
                                                            <i class="bi bi-person-check"></i> Activar
                                                        </button>
                                                    @endif
                                                </form>
                                            @else
                                                <span class="text-muted small">
                                                    <i class="bi bi-person-fill"></i> Tu cuenta
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($usuarios->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-4 px-3">
                                <div class="text-muted">
                                    Mostrando {{ $usuarios->firstItem() }} a {{ $usuarios->lastItem() }}
                                    de {{ $usuarios->total() }} registros
                                </div>
                                {{ $usuarios->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-toggle').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const nombre = this.dataset.nombre;
                const accion = this.dataset.accion;
                Swal.fire({
                    title: `¿${accion.charAt(0).toUpperCase() + accion.slice(1)} a ${nombre}?`,
                    text: accion === 'desactivar' ?
                        'No podrá ingresar al sistema, pero sus datos se conservan.' :
                        'El usuario podrá volver a ingresar al sistema.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: accion === 'desactivar' ? '#e07b00' : '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: `Sí, ${accion}`,
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
@endsection
