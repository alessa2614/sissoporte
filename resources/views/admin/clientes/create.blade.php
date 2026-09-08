@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Nuevo Cliente</h3>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Llene los campos del formulario</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.clientes.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="nombre">Nombre completo (*)</label>
                            <input type="text" name="nombre" id="nombre" class="form-control"
                                placeholder="Nombre del cliente" value="{{ old('nombre') }}">
                            @error('nombre')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="celular">Celular (*)</label>
                            <input type="text" name="celular" id="celular" class="form-control"
                                placeholder="Ej: 951234567" value="{{ old('celular') }}">
                            @error('celular')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="correo">Correo electrónico (opcional)</label>
                            <input type="email" name="correo" id="correo" class="form-control"
                                placeholder="correo@ejemplo.com" value="{{ old('correo') }}">
                            @error('correo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Registrar
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
