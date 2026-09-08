@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="text-center" style="max-width: 480px;">

            {{-- Número grande --}}
            <div
                style="font-size: 120px; font-weight: 900; line-height: 1;
                    background: linear-gradient(135deg, #F07820, #ff9a4d);
                    -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                403
            </div>

            {{-- Ícono --}}
            <div class="mb-3">
                <span style="font-size: 56px;">🚫</span>
            </div>

            <h2 class="fw-bold mb-2">Acceso Denegado</h2>
            <p class="text-muted mb-4" style="font-size: 15px; line-height: 1.7;">
                No tienes los permisos necesarios para acceder a esta página.<br>
                Contacta al administrador si crees que esto es un error.
            </p>

            <a href="{{ route('admin.dashboard') }}" class="btn btn-lg px-5 py-2 fw-semibold text-white"
                style="background: linear-gradient(135deg, #F07820, #ff9a4d);
                  border: none; border-radius: 50px;
                  box-shadow: 0 6px 20px rgba(240,120,32,.35);
                  transition: all .25s ease;">
                <i class="bi bi-house me-2"></i> Volver al Inicio
            </a>

        </div>
    </div>

    <style>
        a[href="{{ route('admin.dashboard') }}"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(240, 120, 32, .5) !important;
        }
    </style>
@endsection
