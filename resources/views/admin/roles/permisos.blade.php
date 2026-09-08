@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Permisos del Rol: <strong>{{ $role->name }}</strong></h3>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Asignar permisos</h4>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.roles.permisos.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                @php
                    $grupos = [
                        'Dashboard' => $permisos->filter(fn($p) => str_starts_with($p->name, 'dashboard.')),
                        'Órdenes' => $permisos->filter(fn($p) => str_starts_with($p->name, 'ordenes.')),
                        'Clientes' => $permisos->filter(fn($p) => str_starts_with($p->name, 'clientes.')),
                        'Estados' => $permisos->filter(fn($p) => str_starts_with($p->name, 'estados.')),
                        'Garantías' => $permisos->filter(fn($p) => str_starts_with($p->name, 'garantias.')),
                        'Ingresos' => $permisos->filter(fn($p) => str_starts_with($p->name, 'ingresos.')),
                        'Reportes' => $permisos->filter(fn($p) => str_starts_with($p->name, 'reportes.')),
                        'Usuarios' => $permisos->filter(fn($p) => str_starts_with($p->name, 'usuarios.')),
                        'Roles' => $permisos->filter(fn($p) => str_starts_with($p->name, 'roles.')),
                        'Tipos de Equipo' => $permisos->filter(fn($p) => str_starts_with($p->name, 'tipos_equipo.')),
                        'Servicios' => $permisos->filter(fn($p) => str_starts_with($p->name, 'servicios.')),
                    ];
                @endphp

                <div class="row g-4">
                    @foreach ($grupos as $modulo => $lista)
                        @if ($lista->count() > 0)
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <div class="card-header py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-bold">{{ $modulo }}</h6>
                                            <div class="form-check mb-0">
                                                <input class="form-check-input check-all" type="checkbox"
                                                    data-grupo="{{ Str::slug($modulo) }}" title="Seleccionar todos"
                                                    {{ $lista->every(fn($p) => $role->hasPermissionTo($p->name)) ? 'checked' : '' }}>
                                                <label class="form-check-label small text-muted">Todos</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body py-2">
                                        @foreach ($lista as $permiso)
                                            <div class="form-check mb-1 permiso-{{ Str::slug($modulo) }}">
                                                <input class="form-check-input permiso-check" type="checkbox"
                                                    name="permisos[]" value="{{ $permiso->name }}"
                                                    id="p_{{ $permiso->id }}" data-grupo="{{ Str::slug($modulo) }}"
                                                    {{ $role->hasPermissionTo($permiso->name) ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="p_{{ $permiso->id }}">
                                                    {{ $permiso->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <hr class="mt-4">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar permisos
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.check-all').forEach(function(chk) {
            chk.addEventListener('change', function() {
                const grupo = this.dataset.grupo;
                document.querySelectorAll(`.permiso-check[data-grupo="${grupo}"]`)
                    .forEach(c => c.checked = this.checked);
            });
        });

        document.querySelectorAll('.permiso-check').forEach(function(chk) {
            chk.addEventListener('change', function() {
                const grupo = this.dataset.grupo;
                const todos = document.querySelectorAll(`.permiso-check[data-grupo="${grupo}"]`);
                const checkAll = document.querySelector(`.check-all[data-grupo="${grupo}"]`);
                checkAll.checked = [...todos].every(c => c.checked);
            });
        });
    </script>
@endsection
