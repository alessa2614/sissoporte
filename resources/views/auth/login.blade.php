<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/auth.css') }}">
    <style>
        body,
        #auth {
            background-color: #fff5ee !important;
        }

        #auth-left {
            background-color: #fff5ee !important;
        }
    </style>
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo text-center mb-4">
                        <a href="index.html"><img src="{{ asset('assets/compiled/imagen/image.png') }}" alt="Logo"
                                style="width:350px; height:auto;"></a>
                        <h6 class="mt-2 fw-bold">Sistema de Soporte</h6>
                        <p class="text-muted">Nexos Juliaca</p>
                    </div>
                    <h1 class="auth-title">Iniciar Sesión</h1>
                    <p class="auth-subtitle mb-3">
                        Ingrese sus credenciales para acceder al sistema.
                    </p>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <div class="form-check form-check-lg d-flex align-items-end">
                            <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                Mantenerme conectado
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg shadow-sm mt-4">
                            Ingresar
                        </button>
                        {{-- Botón Regresar --}}
                        <a href="{{ url('/') }}" class="btn w-100 btn-lg mt-2"
                            style="background-color: #ff7043; color: #fff; border: none; border-radius: 8px; font-weight: 500;">
                            <i class="bi bi-arrow-left me-2"></i> Regresar
                        </a>
                    </form>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                    <img src="{{ asset('assets/compiled/imagen/tienda.jpg') }}" alt="Login Image"
                        style="width:100%; height:920px; object-fit:cover;">
                </div>
            </div>
        </div>
    </div>
</body>

</html>
