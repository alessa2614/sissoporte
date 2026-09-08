@extends('layouts.admin')

@section('content')
<div class="page-heading mb-3">
    <h3>Mi Perfil</h3>
</div>

{{-- ALERTAS --}}
@if(session('mensaje'))
<div class="alert alert-{{ session('icono') == 'success' ? 'success' : 'danger' }} alert-dismissible fade show">
    {{ session('mensaje') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">

    {{-- PERFIL LATERAL --}}
    <div class="col-lg-4 col-md-5">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">

                {{-- Avatar con inicial --}}
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                    style="width:110px;height:110px;background:#435ebe;color:white;font-size:40px;">
                    {{ strtoupper(substr($usuario->name,0,1)) }}
                </div>

                <h5 class="mb-0">{{ $usuario->name }}</h5>
                <small class="text-muted">{{ $usuario->email }}</small>

                <div class="mt-3">
                    @foreach($usuario->getRoleNames() as $rol)
                        <span class="badge bg-light-primary">{{ $rol }}</span>
                    @endforeach
                </div>

                <hr>

                <div class="text-start small">
                    <p class="mb-2">
                        <i class="bi bi-calendar"></i>
                        <strong> Miembro desde:</strong><br>
                        <span class="text-muted ms-3">
                            {{ $usuario->created_at->format('d/m/Y') }}
                        </span>
                    </p>

                    <p class="mb-0">
                        <i class="bi bi-circle-fill text-success" style="font-size:8px;"></i>
                        <strong> Estado:</strong>
                        <span class="text-success">Activo</span>
                    </p>
                </div>

            </div>
        </div>
    </div>

    {{-- CONTENIDO DERECHO --}}
    <div class="col-lg-8 col-md-7">

        {{-- DATOS PERSONALES --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-person"></i> Datos Personales</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.perfil.actualizar') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre completo</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text"
                                    name="name"
                                    value="{{ old('name',$usuario->name) }}"
                                    class="form-control @error('name') is-invalid @enderror">
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email"
                                    name="email"
                                    value="{{ old('email',$usuario->email) }}"
                                    class="form-control @error('email') is-invalid @enderror">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="text-end">
                        <button class="btn btn-primary px-4">
                            <i class="bi bi-save"></i> Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- SEGURIDAD --}}
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-shield-lock"></i> Seguridad</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.perfil.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Contraseña actual</label>
                        <input type="password"
                            name="password_actual"
                            class="form-control">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nueva contraseña</label>
                            <input type="password"
                                id="password_nuevo"
                                name="password_nuevo"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirmar contraseña</label>
                            <input type="password"
                                name="password_nuevo_confirmation"
                                class="form-control">
                        </div>
                    </div>

                    {{-- Barra mejorada --}}
                    <div class="mb-3">
                        <div class="progress" style="height:6px;">
                            <div id="barraSeguridad" class="progress-bar"></div>
                        </div>
                        <small id="textoSeguridad" class="fw-bold"></small>
                    </div>

                    <div class="text-end">
                        <button class="btn btn-warning px-4">
                            <i class="bi bi-shield-lock"></i> Cambiar contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
document.getElementById('password_nuevo')?.addEventListener('input', function() {
    const val = this.value;
    const barra = document.getElementById('barraSeguridad');
    const texto = document.getElementById('textoSeguridad');
    let score = 0;

    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const niveles = [
        { pct:'0%', color:'#dc3545', label:'' },
        { pct:'25%', color:'#dc3545', label:'Muy débil' },
        { pct:'50%', color:'#ffc107', label:'Débil' },
        { pct:'75%', color:'#0dcaf0', label:'Buena' },
        { pct:'100%', color:'#198754', label:'Muy segura' }
    ];

    barra.style.width = niveles[score].pct;
    barra.style.backgroundColor = niveles[score].color;
    texto.textContent = niveles[score].label;
    texto.style.color = niveles[score].color;
});
</script>

@endsection