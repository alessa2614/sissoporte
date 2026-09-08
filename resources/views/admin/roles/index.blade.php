@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Roles</h3>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Roles registrados
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary" style="float:right;">
                            <i class="bi bi-plus"></i> Nuevo Rol
                        </a>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-lg">
                            <thead>
                                <tr>
                                    <th>Nro</th>
                                    <th>Nombre del Rol</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $nro = ($roles->currentPage() - 1) * $roles->perPage() + 1;
                                @endphp

                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $nro++ }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td class="text-center">
                                            {{-- Botón Permisos --}}
                                            <a href="{{ route('admin.roles.permisos', $role->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="bi bi-shield-lock"></i> Permisos
                                            </a>
                                            <a href="{{ url('/admin/rol/' . $role->id . '/edit') }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>
                                            <form action="{{ url('/admin/rol/' . $role->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-eliminar">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($roles->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-4 px-3">
                                <div class="text-muted">
                                    Mostrando {{ $roles->firstItem() }} a {{ $roles->lastItem() }}
                                    de {{ $roles->total() }} registros
                                </div>
                                <div>
                                    {{ $roles->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-eliminar').forEach(function(btn) {
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
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
