@extends('layouts.web')
@section('titulo', 'Consultar Equipo — Nexos Tiendas Juliaca')

@push('estilos')
    <style>
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(22px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }

        @keyframes pulse-o {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(240, 120, 32, .5)
            }

            70% {
                box-shadow: 0 0 0 9px rgba(240, 120, 32, 0)
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        .fu {
            animation: fadeUp .6s cubic-bezier(.4, 0, .2, 1) both;
        }

        .d1 {
            animation-delay: .08s;
        }

        .d2 {
            animation-delay: .16s;
        }

        .d3 {
            animation-delay: .26s;
        }

        /* ─── HERO ─── */
        .c-hero {
            background: var(--negro);
            padding: 72px 0 56px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .c-hero::before {
            content: '';
            position: absolute;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(240, 120, 32, .11) 0%, transparent 65%);
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .c-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(240, 120, 32, .04) 1px, transparent 1px), linear-gradient(90deg, rgba(240, 120, 32, .04) 1px, transparent 1px);
            background-size: 44px 44px;
            pointer-events: none;
        }

        .c-hero-inner {
            position: relative;
            z-index: 2;
            max-width: 620px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .eyebrow-c {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(240, 120, 32, .12);
            border: 1px solid rgba(240, 120, 32, .25);
            color: #ffc080;
            font-size: .74rem;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 99px;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .c-hero h1 {
            font-family: var(--ff-display);
            font-size: clamp(2rem, 5vw, 2.9rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.13;
            margin-bottom: 12px;
        }

        .c-hero h1 span {
            color: var(--naranja);
        }

        .c-hero-sub {
            font-size: .95rem;
            color: rgba(255, 255, 255, .5);
            line-height: 1.7;
        }

        /* ─── LAYOUT ─── */
        .c-layout {
            max-width: 1100px;
            margin: 0 auto;
            padding: 56px 28px 88px;
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 36px;
            align-items: start;
        }

        @media(max-width:860px) {
            .c-layout {
                grid-template-columns: 1fr;
            }
        }

        /* ─── FORM CARD ─── */
        .form-card {
            background: #fff;
            border: 1px solid var(--borde);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 28px rgba(0, 0, 0, .06);
            position: sticky;
            top: 80px;
        }

        .form-card h3 {
            font-family: var(--ff-display);
            font-size: 1.1rem;
            font-weight: 800;
            margin-bottom: 4px;
            color: var(--texto);
        }

        .form-card p {
            font-size: .82rem;
            color: var(--muted);
            margin-bottom: 20px;
        }

        .form-card label {
            font-size: .83rem;
            font-weight: 600;
            color: #444;
            margin-bottom: 5px;
            display: block;
        }

        .form-card input,
        .form-card select {
            width: 100%;
            border: 1.5px solid var(--borde);
            border-radius: 9px;
            padding: 10px 13px;
            font-size: .92rem;
            color: var(--texto);
            font-family: var(--ff-body);
            margin-bottom: 14px;
            transition: border-color .2s, box-shadow .2s;
            background: #fff;
        }

        .form-card input:focus,
        .form-card select:focus {
            outline: none;
            border-color: var(--naranja);
            box-shadow: 0 0 0 3px rgba(240, 120, 32, .15);
        }

        .btn-search {
            width: 100%;
            background: var(--naranja);
            color: #fff;
            border: none;
            border-radius: 9px;
            padding: 12px;
            font-size: .93rem;
            font-weight: 700;
            cursor: pointer;
            font-family: var(--ff-body);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background .2s, transform .15s;
        }

        .btn-search:hover {
            background: var(--naranja-osc);
            transform: translateY(-1px);
        }

        .btn-search.loading {
            pointer-events: none;
            opacity: .75;
        }

        .spin-icon {
            display: none;
            animation: spin .8s linear infinite;
        }

        .btn-search.loading .search-icon {
            display: none;
        }

        .btn-search.loading .spin-icon {
            display: inline-block;
        }

        .form-hint {
            margin-top: 14px;
            padding-top: 13px;
            border-top: 1px solid var(--borde);
            font-size: .78rem;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-hint i {
            color: var(--naranja);
        }

        /* ─── SECCIÓN RESULTADO ─── */
        .result-area {
            min-height: 400px;
        }

        /* Estado inicial */
        .no-search {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 360px;
            text-align: center;
            color: var(--muted);
            background: var(--gris);
            border-radius: 16px;
            border: 2px dashed var(--borde);
            padding: 40px 24px;
        }

        .no-search i {
            font-size: 2.5rem;
            color: #d0cbc5;
            margin-bottom: 16px;
        }

        .no-search h4 {
            font-family: var(--ff-display);
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--texto);
            margin-bottom: 8px;
        }

        .no-search p {
            font-size: .88rem;
        }

        /* Error */
        .error-card {
            background: #fff5f5;
            border: 1.5px solid #fecdca;
            border-radius: 16px;
            padding: 28px;
            text-align: center;
        }

        .error-card i {
            font-size: 2rem;
            color: #f87171;
            margin-bottom: 12px;
            display: block;
        }

        .error-card h4 {
            font-family: var(--ff-display);
            font-weight: 700;
            color: #b91c1c;
            margin-bottom: 8px;
        }

        .error-card p {
            font-size: .88rem;
            color: #7f1d1d;
            margin-bottom: 18px;
        }

        .btn-wsp-err {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #25d366;
            color: #fff;
            border-radius: 8px;
            padding: 9px 18px;
            font-size: .87rem;
            font-weight: 700;
            text-decoration: none;
            transition: background .2s;
        }

        .btn-wsp-err:hover {
            background: #1db954;
            color: #fff;
        }

        /* ─── RESULTADO PRINCIPAL ─── */
        .result-card {
            background: #fff;
            border: 1px solid var(--borde);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, .06);
            animation: fadeIn .5s ease;
        }

        /* Cabecera del resultado */
        .rc-head {
            background: var(--negro);
            padding: 22px 26px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .rc-codigo {
            font-family: var(--ff-display);
            font-size: 1.2rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: .04em;
        }

        .rc-estado-badge {
            padding: 5px 14px;
            border-radius: 99px;
            font-size: .78rem;
            font-weight: 700;
        }

        /* Fotos del equipo */
        .rc-foto {
            padding: 20px 26px;
            border-bottom: 1px solid var(--borde);
            background: #fafaf9;
        }

        .rc-foto img {
            width: 100%;
            max-width: 320px;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid var(--borde);
            display: block;
        }

        .rc-foto-placeholder {
            width: 100%;
            max-width: 320px;
            height: 160px;
            border-radius: 10px;
            background: var(--gris);
            border: 2px dashed var(--borde);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: var(--muted);
            gap: 6px;
            font-size: .83rem;
        }

        .rc-foto-placeholder i {
            font-size: 1.6rem;
        }

        /* Info básica */
        .rc-info {
            padding: 20px 26px;
            border-bottom: 1px solid var(--borde);
        }

        .rc-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        @media(max-width:560px) {
            .rc-info-grid {
                grid-template-columns: 1fr;
            }
        }

        .rc-field {}

        .rc-field-label {
            font-size: .73rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--muted);
            font-weight: 600;
            margin-bottom: 3px;
        }

        .rc-field-value {
            font-size: .9rem;
            font-weight: 600;
            color: var(--texto);
        }

        /* Timeline */
        .rc-timeline {
            padding: 22px 26px;
            border-bottom: 1px solid var(--borde);
        }

        .tl-title {
            font-family: var(--ff-display);
            font-size: .95rem;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--texto);
        }

        .tl-item {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .tl-left {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .tl-dot {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            flex-shrink: 0;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            font-weight: 700;
        }

        .tl-dot.done {
            background: #22c55e;
            color: #fff;
        }

        .tl-dot.active {
            background: var(--naranja);
            color: #fff;
            animation: pulse-o 2s infinite;
        }

        .tl-dot.pending {
            background: #e7e3de;
            color: #aaa;
        }

        .tl-line {
            width: 2px;
            flex: 1;
            min-height: 18px;
            background: #e7e3de;
            margin: 3px 0;
        }

        .tl-line.done {
            background: #22c55e;
        }

        .tl-content {
            padding-bottom: 18px;
        }

        .tl-label {
            font-size: .88rem;
            font-weight: 600;
            color: var(--texto);
        }

        .tl-label.active {
            color: var(--naranja);
        }

        .tl-label.pending {
            color: #bbb;
        }

        .tl-sub {
            font-size: .78rem;
            color: var(--muted);
            margin-top: 2px;
        }

        .tl-now {
            font-size: .7rem;
            background: rgba(240, 120, 32, .12);
            color: var(--naranja);
            border-radius: 99px;
            padding: 2px 8px;
            margin-left: 6px;
            font-weight: 700;
        }

        /* Detalles adicionales */
        .rc-extras {
            padding: 20px 26px;
            border-bottom: 1px solid var(--borde);
        }

        .ext-title {
            font-family: var(--ff-display);
            font-size: .93rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--texto);
        }

        .ext-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f5f2ef;
            font-size: .87rem;
        }

        .ext-row:last-child {
            border: none;
        }

        .ext-key {
            color: var(--muted);
        }

        .ext-val {
            font-weight: 600;
            color: var(--texto);
        }

        .ext-val.verde {
            color: #16a34a;
        }

        .ext-val.naranja {
            color: var(--naranja);
        }

        .ext-val.rojo {
            color: #dc2626;
        }

        /* Costo */
        .rc-costo {
            padding: 18px 26px;
            border-bottom: 1px solid var(--borde);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            background: #fafaf9;
        }

        .costo-label {
            font-size: .82rem;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .costo-val {
            font-family: var(--ff-display);
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--naranja);
        }

        .pago-badge {
            padding: 4px 12px;
            border-radius: 99px;
            font-size: .76rem;
            font-weight: 700;
        }

        /* Footer del resultado */
        .rc-footer {
            padding: 18px 26px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-wa {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #25d366;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            padding: 9px 18px;
            font-size: .86rem;
            font-weight: 700;
            transition: background .2s;
        }

        .btn-wa:hover {
            background: #1db954;
            color: #fff;
        }

        .btn-reload {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--gris);
            color: var(--texto);
            text-decoration: none;
            border-radius: 8px;
            padding: 9px 18px;
            font-size: .86rem;
            font-weight: 600;
            border: 1.5px solid var(--borde);
            transition: all .2s;
            cursor: pointer;
            font-family: var(--ff-body);
            background: #fff;
        }

        .btn-reload:hover {
            border-color: var(--naranja);
            color: var(--naranja);
        }

        /* ─── BADGES ESTADOS ─── */
        .badge-recibido {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-revision {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-espera {
            background: #fff7ed;
            color: #9a3412;
        }

        .badge-reparacion {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-listo {
            background: #dcfce7;
            color: #14532d;
        }

        .badge-entregado {
            background: #f3f4f6;
            color: #374151;
        }

        /* ─── INFO LATERAL ─── */
        .info-side {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 24px;
        }

        .info-side-card {
            background: #fff;
            border: 1px solid var(--borde);
            border-radius: 14px;
            padding: 20px;
        }

        .isc-title {
            font-family: var(--ff-display);
            font-size: .9rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--texto);
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .isc-title i {
            color: var(--naranja);
        }

        .isc-row {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: .85rem;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .isc-row:last-child {
            margin-bottom: 0;
        }

        .isc-row i {
            color: var(--naranja);
            flex-shrink: 0;
        }

        .isc-row a {
            color: var(--muted);
            text-decoration: none;
        }

        .isc-row a:hover {
            color: var(--naranja);
        }

        .tip-card {
            background: var(--negro);
            border-radius: 14px;
            padding: 20px;
            color: rgba(255, 255, 255, .7);
        }

        .tip-card .tip-title {
            font-family: var(--ff-display);
            font-weight: 700;
            color: #fff;
            font-size: .9rem;
            margin-bottom: 10px;
        }

        .tip-item {
            display: flex;
            gap: 8px;
            align-items: flex-start;
            margin-bottom: 8px;
            font-size: .83rem;
        }

        .tip-item i {
            color: var(--naranja);
            flex-shrink: 0;
            margin-top: 2px;
        }
    </style>
@endpush

@section('content')

    {{-- ─── HERO ─── --}}
    <div class="c-hero">
        <div class="c-hero-inner">
            <div class="eyebrow-c fu d1"><i class="bi bi-search"></i> Seguimiento en tiempo real</div>
            <h1 class="fu d2">Consulta el estado de <span>tu equipo</span></h1>
            <p class="c-hero-sub fu d3">
                Ingresa el código de tu ticket y tu número de celular para ver el estado actualizado al instante.
            </p>
        </div>
    </div>

    {{-- ─── LAYOUT ─── --}}
    <div class="c-layout">

        {{-- ─── FORMULARIO (izquierda, sticky) ─── --}}
        <div>
            <div class="form-card fu d1">
                <h3><i class="bi bi-search" style="color:var(--naranja);margin-right:6px;"></i>Buscar mi equipo</h3>
                <p>Ingresa los datos de tu ticket de recepción</p>

                <form id="formConsulta" action="{{ route('consulta.buscar') }}" method="POST">
                    @csrf
                    <label>Código de orden</label>
                    <input type="text" name="codigo" value="{{ old('codigo', $codigo ?? '') }}"
                        placeholder="NX-2026-0042" style="text-transform:uppercase; letter-spacing:.04em;" required>

                    <label>Número de celular</label>
                    <input type="text" name="celular" value="{{ old('celular', $celular ?? '') }}"
                        placeholder="Ej: 951234567" maxlength="12" required>

                    <button type="submit" class="btn-search" id="btnBuscar">
                        <i class="bi bi-search search-icon"></i>
                        <i class="bi bi-arrow-clockwise spin-icon"></i>
                        Consultar estado
                    </button>
                </form>

                <div class="form-hint">
                    <i class="bi bi-info-circle-fill"></i>
                    El código está en el ticket que recibiste al dejar tu equipo.
                </div>
            </div>

            {{-- Info lateral --}}
            <div class="info-side">
                <div class="info-side-card">
                    <div class="isc-title"><i class="bi bi-geo-alt-fill"></i> Dónde encontrarnos</div>
                    <div class="isc-row"><i class="bi bi-map-fill"></i><span>Jr. Ayaviri 741, Plaza San José, Juliaca</span>
                    </div>
                    <div class="isc-row"><i class="bi bi-whatsapp" style="color:#25d366;"></i><a
                            href="https://wa.me/51986560904">+51 986 560 904</a></div>
                    <div class="isc-row"><i class="bi bi-clock-fill"></i><span>Lun–Vie 9am–7pm · Sáb 9am–2pm</span></div>
                </div>
                <div class="tip-card">
                    <div class="tip-title"><i class="bi bi-lightbulb me-1" style="color:var(--naranja-lite);"></i> Tips
                    </div>
                    <div class="tip-item"><i class="bi bi-dot"></i><span>Tu código tiene el formato NX-AÑO-NÚMERO</span>
                    </div>
                    <div class="tip-item"><i class="bi bi-dot"></i><span>Usa el número exacto con el que te
                            registraste</span></div>
                    <div class="tip-item"><i class="bi bi-dot"></i><span>¿Problema? Escríbenos por WhatsApp</span></div>
                </div>
            </div>
        </div>

        {{-- ─── RESULTADO (derecha) ─── --}}
        <div class="result-area">

            {{-- Estado inicial --}}
            @if (!isset($orden) && !isset($error))
                <div class="no-search">
                    <i class="bi bi-search"></i>
                    <h4>Ingresa los datos de tu ticket</h4>
                    <p>El resultado de la consulta aparecerá aquí de manera inmediata.</p>
                </div>
            @endif

            {{-- Error --}}
            @if (isset($error))
                <div class="error-card">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <h4>No encontramos ninguna orden</h4>
                    <p>
                        No encontramos una orden con el código <strong>{{ $codigo ?? '' }}</strong> y el celular
                        <strong>{{ $celular ?? '' }}</strong>. Verifica que ambos datos sean correctos.
                    </p>
                    <a href="https://wa.me/51986560904?text=Hola,%20no%20puedo%20encontrar%20mi%20orden.%20Mi%20código%20es%20{{ $codigo ?? '' }}"
                        target="_blank" class="btn-wsp-err">
                        <i class="bi bi-whatsapp"></i> Pedir ayuda por WhatsApp
                    </a>
                </div>
            @endif

            {{-- ─── RESULTADO ENCONTRADO ─── --}}
            @if (isset($orden))
                @php
                    $estados = [
                        'recibido',
                        'en_revision',
                        'esperando_aprobacion',
                        'en_reparacion',
                        'listo',
                        'entregado',
                    ];
                    $labels = [
                        'Recibido',
                        'En Revisión',
                        'Esperando Aprobación',
                        'En Reparación',
                        'Listo para Recoger',
                        'Entregado',
                    ];
                    $badges = [
                        'badge-recibido',
                        'badge-revision',
                        'badge-espera',
                        'badge-reparacion',
                        'badge-listo',
                        'badge-entregado',
                    ];
                    $posAct = array_search($orden->estado, $estados);
                @endphp

                <div class="result-card">

                    {{-- Cabecera: código + estado --}}
                    <div class="rc-head">
                        <div>
                            <div
                                style="font-size:.73rem;text-transform:uppercase;letter-spacing:.06em;color:rgba(255,255,255,.45);margin-bottom:4px;">
                                Orden encontrada</div>
                            <div class="rc-codigo">{{ $orden->codigo }}</div>
                        </div>
                        <span class="rc-estado-badge {{ $badges[$posAct] ?? 'badge-entregado' }}">
                            {{ $labels[$posAct] ?? $orden->estado }}
                        </span>
                    </div>

                    {{-- Foto del equipo --}}
                    <div class="rc-foto">
                        @if ($orden->foto)
                            <img src="{{ url('storage/' . $orden->foto) }}" alt="Foto del equipo"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="rc-foto-placeholder" style="display:none;">
                                <i class="bi bi-laptop"></i>
                                <span>No se pudo cargar la foto</span>
                            </div>
                        @else
                            <div class="rc-foto-placeholder">
                                <i class="bi bi-laptop"></i>
                                <span>Sin foto del equipo</span>
                            </div>
                        @endif
                    </div>

                    {{-- Info básica --}}
                    <div class="rc-info">
                        <div class="rc-info-grid">
                            <div class="rc-field">
                                <div class="rc-field-label">Cliente</div>
                                <div class="rc-field-value">{{ $orden->cliente->nombre }}</div>
                            </div>
                            <div class="rc-field">
                                <div class="rc-field-label">Celular</div>
                                <div class="rc-field-value">{{ $orden->cliente->celular }}</div>
                            </div>
                            <div class="rc-field">
                                <div class="rc-field-label">Tipo de equipo</div>
                                <div class="rc-field-value">{{ $orden->tipoEquipo->nombre }}</div>
                            </div>
                            @if ($orden->marca)
                                <div class="rc-field">
                                    <div class="rc-field-label">Marca / Modelo</div>
                                    <div class="rc-field-value">{{ $orden->marca }} {{ $orden->modelo }}</div>
                                </div>
                            @endif
                            @if ($orden->tecnico)
                                <div class="rc-field">
                                    <div class="rc-field-label">Técnico asignado</div>
                                    <div class="rc-field-value">{{ $orden->tecnico->name }}</div>
                                </div>
                            @endif
                            <div class="rc-field">
                                <div class="rc-field-label">Fecha de ingreso</div>
                                <div class="rc-field-value">{{ $orden->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>

                        @if ($orden->descripcion)
                            <div style="margin-top:14px; padding-top:14px; border-top:1px solid var(--borde);">
                                <div class="rc-field-label" style="margin-bottom:5px;">Descripción del problema</div>
                                <div style="font-size:.88rem; color:var(--texto); line-height:1.6;">
                                    {{ $orden->descripcion }}</div>
                            </div>
                        @endif
                    </div>

                    {{-- Timeline --}}
                    <div class="rc-timeline">
                        <div class="tl-title"><i class="bi bi-diagram-3 me-2" style="color:var(--naranja);"></i>Progreso
                            de
                            la reparación</div>
                        @foreach ($estados as $i => $est)
                            @php
                                $tipo = $i < $posAct ? 'done' : ($i === $posAct ? 'active' : 'pending');
                                $isLast = $i === count($estados) - 1;
                                $subtexts = [
                                    'Equipo recibido en el taller',
                                    'Técnico evaluando el problema',
                                    'Esperando tu confirmación',
                                    'Trabajando en la reparación',
                                    '¡Tu equipo está listo!',
                                    'Reparación completada',
                                ];
                            @endphp
                            <div class="tl-item">
                                <div class="tl-left">
                                    <div class="tl-dot {{ $tipo }}">
                                        @if ($tipo === 'done')
                                            <i class="bi bi-check"></i>
                                        @elseif($tipo === 'active')
                                            <i class="bi bi-circle-fill" style="font-size:.5rem;"></i>
                                        @else
                                            {{ $i + 1 }}
                                        @endif
                                    </div>
                                    @if (!$isLast)
                                        <div class="tl-line {{ $tipo === 'done' ? 'done' : '' }}"></div>
                                    @endif
                                </div>
                                <div class="tl-content">
                                    <div class="tl-label {{ $tipo }}">
                                        {{ $labels[$i] }}
                                        @if ($tipo === 'active')
                                            <span class="tl-now">⚡ Ahora</span>
                                        @endif
                                    </div>
                                    <div class="tl-sub">{{ $subtexts[$i] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Adicionales si existen --}}
                    @if ($orden->adicionales->count() > 0)
                        <div class="rc-extras">
                            <div class="ext-title"><i class="bi bi-plus-circle me-2"
                                    style="color:var(--naranja);"></i>Trabajos adicionales</div>
                            @foreach ($orden->adicionales as $ad)
                                <div class="ext-row">
                                    <span class="ext-key">{{ $ad->descripcion }}</span>
                                    <span
                                        class="ext-val {{ $ad->estado === 'aprobado' ? 'verde' : ($ad->estado === 'rechazado' ? 'rojo' : 'naranja') }}">
                                        {{ ucfirst($ad->estado) }} — S/. {{ number_format($ad->costo, 2) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Garantía --}}
                    @if ($orden->garantia && $orden->estado === 'entregado')
                        <div class="rc-extras">
                            <div class="ext-title"><i class="bi bi-shield-check me-2" style="color:#22c55e;"></i>Garantía
                                del trabajo</div>
                            <div class="ext-row">
                                <span class="ext-key">Vigencia</span>
                                <span class="ext-val verde">{{ $orden->garantia->dias_garantia }} días</span>
                            </div>
                            <div class="ext-row">
                                <span class="ext-key">Válido hasta</span>
                                <span
                                    class="ext-val">{{ \Carbon\Carbon::parse($orden->garantia->fecha_fin)->format('d/m/Y') }}</span>
                            </div>
                            @if ($orden->garantia->observacion)
                                <div class="ext-row">
                                    <span class="ext-key">Observación</span>
                                    <span class="ext-val"
                                        style="max-width:260px;text-align:right;">{{ $orden->garantia->observacion }}</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Total --}}
                    @if ($orden->total_final || $orden->costo_estimado)
                        <div class="rc-costo">
                            <div>
                                <div class="costo-label">{{ $orden->total_final ? 'Total final' : 'Costo estimado' }}
                                </div>
                                <div class="costo-val">
                                    S/. {{ number_format($orden->total_final ?? $orden->costo_estimado, 2) }}
                                </div>
                            </div>
                            <span
                                class="pago-badge {{ $orden->estado_pago === 'pagado' ? 'badge-listo' : 'badge-espera' }}">
                                {{ $orden->estado_pago === 'pagado' ? '✓ Pagado' : '⏳ Pago pendiente' }}
                            </span>
                        </div>
                    @endif

                    {{-- Footer acciones --}}
                    <div class="rc-footer">
                        <a href="https://wa.me/51986560904?text=Hola,%20quiero%20consultar%20sobre%20mi%20orden%20{{ $orden->codigo }}"
                            target="_blank" class="btn-wa">
                            <i class="bi bi-whatsapp"></i> Consultar por WhatsApp
                        </a>
                        <button class="btn-reload" onclick="window.location.reload()">
                            <i class="bi bi-arrow-clockwise"></i> Actualizar
                        </button>
                    </div>

                </div>
            @endif

        </div>{{-- /result-area --}}
    </div>{{-- /c-layout --}}

@endsection

@push('scripts')
    <script>
        // Spinner al enviar el formulario
        document.getElementById('formConsulta').addEventListener('submit', function() {
            document.getElementById('btnBuscar').classList.add('loading');
        });
    </script>
@endpush
