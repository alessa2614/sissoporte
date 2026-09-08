@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Tipos de Equipo</h3>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Tipos registrados</h4>
                </div>
                <div class="card-body">

                    {{-- Formulario agregar --}}
                    <form action="{{ route('tipo_equipos.store') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="nombre" class="form-control"
                                placeholder="Ej: Laptop, PC, Celular..." value="{{ old('nombre') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus"></i> Agregar
                            </button>
                        </div>
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nro</th>
                                    <th>Nombre</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $nro = ($tipos->currentPage() - 1) * $tipos->perPage() + 1;
                                @endphp

                                @foreach ($tipos as $tipo)
                                    <tr>
                                        <td>{{ $nro++ }}</td>
                                        <td>{{ $tipo->nombre }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('tipo_equipos.edit', $tipo->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>
                                            <form action="{{ route('tipo_equipos.destroy', $tipo->id) }}" method="POST"
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

                        @if ($tipos->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-4 px-3">
                                <div class="text-muted">
                                    Mostrando {{ $tipos->firstItem() }} a {{ $tipos->lastItem() }}
                                    de {{ $tipos->total() }} registros
                                </div>
                                <div>
                                    {{ $tipos->links('pagination::bootstrap-4') }}
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
