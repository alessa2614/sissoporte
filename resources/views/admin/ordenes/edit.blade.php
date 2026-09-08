@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Editar Orden <span class="text-muted fs-5">{{ $orden->codigo }}</span></h3>
            <a href="{{ route('admin.ordenes.show', $orden) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver a la orden
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Corrige los siguientes errores:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Info del cliente (solo lectura) --}}
    <div class="alert alert-secondary d-flex align-items-center gap-2 py-2">
        <i class="bi bi-person-circle"></i>
        <span>
            <strong>Cliente:</strong> {{ $orden->cliente->nombre }}
            &nbsp;·&nbsp;
            <strong>Celular:</strong> {{ $orden->cliente->celular }}
            &nbsp;·&nbsp;
            <strong>Código:</strong> {{ $orden->codigo }}
        </span>
        <span class="ms-auto text-muted small">Los datos del cliente no se pueden cambiar desde aquí</span>
    </div>

    <form action="{{ route('admin.ordenes.update', $orden) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">

            {{-- DATOS DEL EQUIPO --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="bi bi-laptop"></i> Datos del Equipo</h4>
                    </div>
                    <div class="card-body">

                        <div class="form-group mb-3">
                            <label>Tipo de equipo (*)</label>
                            <select name="tipo_equipo_id"
                                class="form-control @error('tipo_equipo_id') is-invalid @enderror">
                                <option value="">-- Seleccione tipo --</option>
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id }}"
                                        {{ old('tipo_equipo_id', $orden->tipo_equipo_id) == $tipo->id ? 'selected' : '' }}>
                                        {{ $tipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_equipo_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Marca</label>
                                    <input type="text" name="marca" class="form-control"
                                        placeholder="Ej: HP, Dell, Samsung" value="{{ old('marca', $orden->marca) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Modelo</label>
                                    <input type="text" name="modelo" class="form-control" placeholder="Ej: Pavilion 15"
                                        value="{{ old('modelo', $orden->modelo) }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>Foto del equipo (opcional)</label>

                            @if ($orden->foto)
                                <div class="mb-2 d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/' . $orden->foto) }}" alt="Foto actual"
                                        style="height:80px;border-radius:8px;border:1px solid #dee2e6;object-fit:cover;"
                                        onerror="this.style.display='none'">
                                    <div>
                                        <div class="text-muted small mb-1">Foto actual</div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="eliminar_foto"
                                                id="eliminarFoto" value="1">
                                            <label class="form-check-label text-danger small" for="eliminarFoto">
                                                Eliminar foto actual
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <small class="text-muted">
                                {{ $orden->foto ? 'Sube una nueva imagen para reemplazar la actual.' : 'Sin foto aún. Puedes subir una.' }}
                            </small>
                        </div>

                    </div>
                </div>
            </div>

            {{-- DESCRIPCIÓN Y TÉCNICO --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="bi bi-tools"></i> Servicio</h4>
                    </div>
                    <div class="card-body">

                        <div class="form-group mb-3">
                            <label>Descripción del problema (*)</label>
                            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="5"
                                placeholder="Describe el problema que reporta el cliente">{{ old('descripcion', $orden->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Técnico asignado</label>
                            <select name="tecnico_id" class="form-control">
                                <option value="">-- Sin asignar --</option>
                                @foreach ($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->id }}"
                                        {{ old('tecnico_id', $orden->tecnico_id) == $tecnico->id ? 'selected' : '' }}>
                                        {{ $tecnico->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Info de costos: solo lectura --}}
                        <div class="alert alert-light border py-2 mb-0">
                            <div class="text-muted small mb-1">
                                <i class="bi bi-info-circle"></i>
                                Los costos y servicios se gestionan desde la vista de la orden
                            </div>
                            <div class="d-flex gap-3">
                                <span>
                                    <strong>Costo estimado:</strong>
                                    S/. {{ number_format($orden->costo_estimado ?? 0, 2) }}
                                </span>
                                @if ($orden->total_final)
                                    <span>
                                        <strong>Total final:</strong>
                                        S/. {{ number_format($orden->total_final, 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- BOTONES --}}
        <div class="row mt-2 mb-4">
            <div class="col-md-12 d-flex gap-2">
                <a href="{{ route('admin.ordenes.show', $orden) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar Cambios
                </button>
            </div>
        </div>

    </form>
@endsection
