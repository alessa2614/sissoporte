@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Editar Tipo de Equipo</h3>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h4>Modificar nombre</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('tipo_equipos.update', $tipo->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="nombre">Nombre (*)</label>
                            <input type="text" name="nombre" id="nombre" class="form-control"
                                value="{{ old('nombre', $tipo->nombre) }}">
                            @error('nombre')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <a href="{{ route('tipo_equipos.index') }}" class="btn btn-secondary">Cancelar</a>
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
