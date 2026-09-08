@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Editar Cliente</h3>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Modificar datos del cliente</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.clientes.update', $cliente->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="nombre">Nombre completo (*)</label>
                            <input type="text" name="nombre" id="nombre" class="form-control"
                                value="{{ old('nombre', $cliente->nombre) }}">
                            @error('nombre')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="celular">Celular (*)</label>
                            <input type="text" name="celular" id="celular" class="form-control"
                                value="{{ old('celular', $cliente->celular) }}">
                            @error('celular')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="correo">Correo electrónico (opcional)</label>
                            <input type="email" name="correo" id="correo" class="form-control"
                                value="{{ old('correo', $cliente->correo) }}">
                            @error('correo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Actualizar
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
