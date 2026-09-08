@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Editar Usuario</h3>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Modificar datos del usuario</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="name">Nombre completo (*)</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name', $usuario->name) }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Correo electrónico (*)</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ old('email', $usuario->email) }}">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">
                                Nueva contraseña
                                <small class="text-muted">(dejar vacío para no cambiar)</small>
                            </label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Mínimo 8 caracteres">
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation">Confirmar nueva contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label for="role">Rol (*)</label>
                            <select name="role" id="role" class="form-control">
                                <option value="">-- Seleccione un rol --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ $usuario->getRoleNames()->first() == $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Estado</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="estado" id="estado" value="1"
                                    {{ old('estado', $usuario->estado) ? 'checked' : '' }}>
                                <label class="form-check-label" for="estado">
                                    Usuario activo
                                </label>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
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
