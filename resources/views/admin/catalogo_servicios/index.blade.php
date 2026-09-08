@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Catálogo de Servicios</h3>
    </div>

    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4>Servicios registrados</h4>
                </div>
                <div class="card-body">

                    {{-- Formulario agregar --}}
                    <form action="{{ route('catalogo_servicios.store') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-7">
                                <input type="text" name="nombre" class="form-control" placeholder="Nombre del servicio"
                                    value="{{ old('nombre') }}">
                                @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text">S/.</span>
                                    <input type="number" name="precio_base" class="form-control" placeholder="0.00"
                                        step="0.01" min="0" value="{{ old('precio_base') }}">
                                </div>
                                @error('precio_base')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-plus"></i> Agregar
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nro</th>
                                    <th>Nombre del Servicio</th>
                                    <th>Precio Base</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $nro = ($servicios->currentPage() - 1) * $servicios->perPage() + 1;
                                @endphp

                                @foreach ($servicios as $servicio)
                                    <tr>
                                        <td>{{ $nro++ }}</td>
                                        <td>{{ $servicio->nombre }}</td>
                                        <td>S/. {{ number_format($servicio->precio_base, 2) }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('catalogo_servicios.edit', $servicio->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>
                                            <form action="{{ route('catalogo_servicios.destroy', $servicio->id) }}"
                                                method="POST" style="display:inline-block;">
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

                        @if ($servicios->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-4 px-3">
                                <div class="text-muted">
                                    Mostrando {{ $servicios->firstItem() }} a {{ $servicios->lastItem() }}
                                    de {{ $servicios->total() }} registros
                                </div>
                                <div>
                                    {{ $servicios->links('pagination::bootstrap-4') }}
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
