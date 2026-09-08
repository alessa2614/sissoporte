@extends('layouts.web')
@section('titulo', 'Nexos Tiendas — Servicio Técnico y Tecnología en Juliaca')

@push('estilos')
    <style>
        /* ─── ANIMACIONES ─── */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(28px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes pulse-o {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(240, 120, 32, .5)
            }

            70% {
                box-shadow: 0 0 0 8px rgba(240, 120, 32, 0)
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-8px)
            }
        }

        @keyframes scanline {
            from {
                background-position: 0 0
            }

            to {
                background-position: 0 100%
            }
        }

        .fu {
            animation: fadeUp .7s var(--ease) both;
        }

        .fi {
            animation: fadeIn .8s ease both;
        }

        .d1 {
            animation-delay: .1s;
        }

        .d2 {
            animation-delay: .2s;
        }

        .d3 {
            animation-delay: .3s;
        }

        .d4 {
            animation-delay: .4s;
        }

        .d5 {
            animation-delay: .55s;
        }

        /* ─── HERO ─── */
        .hero {
            min-height: calc(100svh - 68px);
            background: var(--negro);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        /* Fondo: grid de circuito */
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(240, 120, 32, .055) 1px, transparent 1px),
                linear-gradient(90deg, rgba(240, 120, 32, .055) 1px, transparent 1px);
            background-size: 44px 44px;
            pointer-events: none;
        }

        /* Glow central naranja */
        .hero::after {
            content: '';
            position: absolute;
            width: 900px;
            height: 900px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(240, 120, 32, .13) 0%, transparent 65%);
            left: 55%;
            top: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .hero-inner {
            position: relative;
            z-index: 2;
            max-width: 1280px;
            margin: 0 auto;
            padding: 80px 28px 60px;
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 60px;
            align-items: center;
        }

        @media(max-width:1024px) {
            .hero-inner {
                grid-template-columns: 1fr;
            }
        }

        /* Eyebrow */
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(240, 120, 32, .12);
            border: 1px solid rgba(240, 120, 32, .25);
            color: #ffc080;
            border-radius: 99px;
            padding: 5px 14px;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .eyebrow i {
            font-size: .8rem;
            color: var(--naranja);
        }

        /* Título */
        .hero-h1 {
            font-family: var(--ff-display);
            font-size: clamp(2.4rem, 5.5vw, 4rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
            letter-spacing: -.025em;
            margin-bottom: 18px;
        }

        .hero-h1 .accent {
            background: linear-gradient(125deg, var(--naranja) 0%, var(--naranja-lite) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-desc {
            color: rgba(255, 255, 255, .6);
            font-size: 1.05rem;
            line-height: 1.75;
            max-width: 500px;
            margin-bottom: 36px;
        }

        /* Botones */
        .hero-btns {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 48px;
        }

        .btn-p {
            background: var(--naranja);
            color: #fff;
            text-decoration: none;
            border-radius: 9px;
            padding: 13px 26px;
            font-size: .92rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background var(--t), transform var(--t), box-shadow var(--t);
        }

        .btn-p:hover {
            background: var(--naranja-osc);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(240, 120, 32, .4);
        }

        .btn-s {
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .15);
            color: rgba(255, 255, 255, .82);
            text-decoration: none;
            border-radius: 9px;
            padding: 13px 26px;
            font-size: .92rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all var(--t);
        }

        .btn-s:hover {
            background: rgba(255, 255, 255, .12);
            color: #fff;
            border-color: rgba(255, 255, 255, .3);
        }

        /* Stats */
        .hero-stats {
            display: flex;
            gap: 0;
        }

        .stat {
            flex: 1;
            padding: 0 20px 0 0;
            border-right: 1px solid rgba(255, 255, 255, .1);
        }

        .stat:last-child {
            border: none;
        }

        .stat:not(:first-child) {
            padding-left: 20px;
        }

        .stat-n {
            font-family: var(--ff-display);
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }

        .stat-n em {
            color: var(--naranja);
            font-style: normal;
        }

        .stat-l {
            font-size: .74rem;
            color: rgba(255, 255, 255, .45);
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-top: 4px;
        }

        /* Hero Card — estado del equipo */
        .hero-card {
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 18px;
            padding: 26px;
            backdrop-filter: blur(12px);
            animation: float 5s ease-in-out infinite;
        }

        @media(max-width:1024px) {
            .hero-card {
                animation: none;
            }
        }

        .hc-head {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .hc-head-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: rgba(240, 120, 32, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--naranja);
            font-size: 1rem;
        }

        .hc-title {
            font-family: var(--ff-display);
            font-weight: 700;
            color: #fff;
            font-size: .95rem;
        }

        .hc-sub {
            font-size: .78rem;
            color: rgba(255, 255, 255, .4);
        }

        /* Filas de estado */
        .estado-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 9px;
            background: rgba(255, 255, 255, .03);
            margin-bottom: 6px;
            border: 1px solid rgba(255, 255, 255, .05);
            transition: background var(--t);
        }

        .e-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .e-dot.done {
            background: #22c55e;
        }

        .e-dot.active {
            background: var(--naranja);
            animation: pulse-o 1.8s infinite;
        }

        .e-dot.wait {
            background: rgba(255, 255, 255, .18);
        }

        .e-label {
            flex: 1;
            font-size: .85rem;
            color: rgba(255, 255, 255, .75);
        }

        .e-label.active {
            color: var(--naranja-lite);
            font-weight: 600;
        }

        .e-tag {
            font-size: .7rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 99px;
            background: rgba(255, 255, 255, .08);
            color: rgba(255, 255, 255, .55);
        }

        .e-tag.act {
            background: rgba(240, 120, 32, .2);
            color: var(--naranja-lite);
        }

        .hc-footer {
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, .07);
            font-size: .78rem;
            color: rgba(255, 255, 255, .38);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .hc-footer i {
            color: var(--naranja);
        }

        .hc-cta {
            display: block;
            margin-top: 14px;
            text-decoration: none;
            background: var(--naranja);
            color: #fff;
            border-radius: 9px;
            padding: 10px 16px;
            font-size: .87rem;
            font-weight: 700;
            text-align: center;
            transition: background var(--t);
        }

        .hc-cta:hover {
            background: var(--naranja-osc);
            color: #fff;
        }

        /* ─── SECCIONES BASE ─── */
        .sec {
            padding: 88px 0;
        }

        .sec-sm {
            padding: 56px 0;
        }

        .sec-gris {
            background: var(--gris);
        }

        .sec-dark {
            background: var(--negro);
        }

        .sec-wrap {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 28px;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(240, 120, 32, .1);
            color: var(--naranja);
            border: 1px solid rgba(240, 120, 32, .2);
            border-radius: 99px;
            padding: 4px 13px;
            font-size: .73rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            margin-bottom: 12px;
        }

        .tag-w {
            background: rgba(240, 120, 32, .15);
            color: var(--naranja-lite);
            border-color: rgba(240, 120, 32, .3);
        }

        .sec-title {
            font-family: var(--ff-display);
            font-size: clamp(1.75rem, 3.5vw, 2.5rem);
            font-weight: 800;
            line-height: 1.17;
            letter-spacing: -.02em;
            color: var(--texto);
        }

        .sec-title-w {
            color: #fff;
        }

        .sec-lead {
            font-size: 1rem;
            color: var(--muted);
            line-height: 1.72;
            max-width: 540px;
        }

        .sec-lead-w {
            color: rgba(255, 255, 255, .58);
        }

        /* ─── SERVICIOS ─── */
        .srv-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 48px;
        }

        @media(max-width:900px) {
            .srv-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width:560px) {
            .srv-grid {
                grid-template-columns: 1fr;
            }
        }

        .srv-card {
            background: #fff;
            border: 1px solid var(--borde);
            border-radius: 14px;
            padding: 26px;
            transition: all var(--t);
            position: relative;
            overflow: hidden;
        }

        .srv-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--naranja), var(--naranja-lite));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s;
        }

        .srv-card:hover {
            border-color: rgba(240, 120, 32, .28);
            box-shadow: 0 8px 32px rgba(240, 120, 32, .1);
            transform: translateY(-4px);
        }

        .srv-card:hover::after {
            transform: scaleX(1);
        }

        .srv-icon {
            width: 48px;
            height: 48px;
            border-radius: 11px;
            background: rgba(240, 120, 32, .09);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: var(--naranja);
            margin-bottom: 16px;
            transition: all var(--t);
        }

        .srv-card:hover .srv-icon {
            background: var(--naranja);
            color: #fff;
        }

        .srv-name {
            font-family: var(--ff-display);
            font-weight: 700;
            font-size: 1.02rem;
            margin-bottom: 8px;
        }

        .srv-desc {
            font-size: .86rem;
            color: var(--muted);
            line-height: 1.62;
            margin-bottom: 14px;
        }

        .srv-price {
            font-size: .8rem;
            font-weight: 700;
            color: var(--naranja);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* ─── CONSULTA RÁPIDA ─── */
        .c-section {
            background: linear-gradient(140deg, var(--negro) 0%, #1e1008 100%);
            position: relative;
            overflow: hidden;
        }

        .c-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 55% 80% at 78% 50%, rgba(240, 120, 32, .14) 0%, transparent 70%);
            pointer-events: none;
        }

        .c-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 88px 28px;
            display: grid;
            grid-template-columns: 1fr 460px;
            gap: 64px;
            align-items: start;
            position: relative;
            z-index: 2;
        }

        @media(max-width:960px) {
            .c-inner {
                grid-template-columns: 1fr;
            }
        }

        .paso {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .paso-n {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--naranja), var(--naranja-lite));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .88rem;
            font-weight: 800;
            color: #fff;
        }

        .paso-title {
            font-size: .9rem;
            font-weight: 700;
            color: #fff;
        }

        .paso-desc {
            font-size: .82rem;
            color: rgba(255, 255, 255, .45);
            margin-top: 2px;
        }

        /* Form card */
        .fc {
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 18px;
            padding: 30px;
        }

        .fc-title {
            font-family: var(--ff-display);
            font-weight: 700;
            color: #fff;
            font-size: 1.05rem;
            margin-bottom: 4px;
        }

        .fc-sub {
            font-size: .8rem;
            color: rgba(255, 255, 255, .4);
            margin-bottom: 22px;
        }

        .fc label {
            font-size: .83rem;
            font-weight: 600;
            color: rgba(255, 255, 255, .72);
            margin-bottom: 6px;
            display: block;
        }

        .fc input {
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .14);
            color: #fff;
            border-radius: 9px;
            padding: 11px 14px;
            font-size: .93rem;
            width: 100%;
            font-family: var(--ff-body);
            margin-bottom: 14px;
            transition: border-color var(--t), box-shadow var(--t);
        }

        .fc input::placeholder {
            color: rgba(255, 255, 255, .3);
        }

        .fc input:focus {
            outline: none;
            border-color: var(--naranja);
            box-shadow: 0 0 0 3px rgba(240, 120, 32, .2);
        }

        .btn-buscar {
            width: 100%;
            background: var(--naranja);
            color: #fff;
            border: none;
            border-radius: 9px;
            padding: 13px;
            font-size: .93rem;
            font-weight: 700;
            cursor: pointer;
            font-family: var(--ff-body);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background var(--t), transform var(--t);
        }

        .btn-buscar:hover {
            background: var(--naranja-osc);
            transform: translateY(-1px);
        }

        .fc-footer {
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, .07);
            font-size: .77rem;
            color: rgba(255, 255, 255, .35);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .fc-footer i {
            color: var(--naranja);
        }

        .fc-link {
            display: block;
            text-align: center;
            margin-top: 12px;
            color: rgba(255, 255, 255, .4);
            font-size: .82rem;
            text-decoration: none;
            transition: color var(--t);
        }

        .fc-link:hover {
            color: var(--naranja-lite);
        }

        /* ─── NOSOTROS ─── */
        .nos-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 28px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 64px;
            align-items: start;
        }

        @media(max-width:900px) {
            .nos-inner {
                grid-template-columns: 1fr;
            }
        }

        .mv-card {
            border-radius: 13px;
            padding: 22px 24px;
            margin-bottom: 14px;
            color: #fff;
        }

        .mv-label {
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .09em;
            opacity: .65;
            margin-bottom: 6px;
        }

        .mv-text {
            font-size: .9rem;
            line-height: 1.68;
            margin: 0;
            opacity: .9;
        }

        .valor {
            display: flex;
            gap: 13px;
            align-items: flex-start;
            margin-bottom: 18px;
        }

        .valor-ico {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(240, 120, 32, .09);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            color: var(--naranja);
        }

        .valor-name {
            font-weight: 700;
            font-size: .93rem;
            margin-bottom: 2px;
        }

        .valor-desc {
            font-size: .84rem;
            color: var(--muted);
            line-height: 1.57;
        }

        .info-box {
            background: var(--negro2);
            border: 1px solid rgba(255, 255, 255, .07);
            border-radius: 13px;
            padding: 22px 24px;
        }

        .info-box h5 {
            font-family: var(--ff-display);
            color: #fff;
            font-size: .97rem;
            margin-bottom: 14px;
        }

        .irow {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 10px;
            font-size: .875rem;
            color: rgba(255, 255, 255, .7);
        }

        .irow i {
            color: var(--naranja);
            flex-shrink: 0;
        }

        .irow i.wsp {
            color: #25d366;
        }

        .irow a {
            color: rgba(255, 255, 255, .7);
            text-decoration: none;
        }

        .irow a:hover {
            color: var(--naranja-lite);
        }

        /* ─── CTA ─── */
        .cta-section {
            background: linear-gradient(130deg, var(--naranja) 0%, #b84a00 100%);
            padding: 72px 28px;
            text-align: center;
        }

        .cta-section h2 {
            font-family: var(--ff-display);
            font-size: clamp(1.6rem, 3.5vw, 2.3rem);
            font-weight: 800;
            color: #fff;
            margin-bottom: 12px;
        }

        .cta-section p {
            color: rgba(255, 255, 255, .8);
            font-size: 1rem;
            margin-bottom: 30px;
        }

        .btn-wsp {
            background: #fff;
            color: var(--naranja);
            border-radius: 9px;
            padding: 13px 26px;
            font-weight: 700;
            font-size: .93rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all var(--t);
        }

        .btn-wsp:hover {
            background: rgba(255, 255, 255, .9);
            color: var(--naranja-osc);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .2);
        }

        .btn-outline-w {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255, 255, 255, .4);
            border-radius: 9px;
            padding: 13px 26px;
            font-weight: 600;
            font-size: .93rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all var(--t);
        }

        .btn-outline-w:hover {
            border-color: #fff;
            background: rgba(255, 255, 255, .1);
            color: #fff;
        }
    </style>
@endpush

@section('content')

    {{-- ══════════ HERO ══════════ --}}
    <section class="hero">
        <div class="hero-inner">

            {{-- Texto --}}
            <div>
                <div class="eyebrow fu d1">
                    <i class="bi bi-geo-alt-fill"></i>
                    Jr. Ayaviri 741, Plaza San José · Juliaca, Puno
                </div>

                <h1 class="hero-h1 fu d2">
                    Tu <span class="accent">aliado tecnológico</span><br>
                    en Juliaca, Puno
                </h1>

                <p class="hero-desc fu d3">
                    Venta de laptops, PCs, celulares, componentes y servicio técnico especializado. Seguimiento de tu equipo
                    en tiempo real.
                </p>

                <div class="hero-btns fu d4">
                    <a href="{{ url('/consulta') }}" class="btn-p">
                        <i class="bi bi-search"></i> Consultar mi equipo
                    </a>
                    <a href="https://wa.me/51986560904" target="_blank" class="btn-s">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </a>
                </div>

                <div class="hero-stats fu d5">
                    <div class="stat">
                        <div class="stat-n">500<em>+</em></div>
                        <div class="stat-l">Equipos atendidos</div>
                    </div>
                    <div class="stat">
                        <div class="stat-n">30<em>d</em></div>
                        <div class="stat-l">Garantía de trabajo</div>
                    </div>
                    <div class="stat">
                        <div class="stat-n">6<em>+</em></div>
                        <div class="stat-l">Años de experiencia</div>
                    </div>
                </div>
            </div>

            {{-- Card flotante --}}
            <div class="hero-card fi d3">
                <div class="hc-head">
                    <div class="hc-head-icon"><i class="bi bi-clipboard-pulse"></i></div>
                    <div>
                        <div class="hc-title">Estado de tu equipo</div>
                        <div class="hc-sub">Actualización en tiempo real</div>
                    </div>
                </div>

                <div class="estado-row">
                    <div class="e-dot done"></div>
                    <div class="e-label">Recibido</div>
                    <div class="e-tag">✓ Listo</div>
                </div>
                <div class="estado-row">
                    <div class="e-dot done"></div>
                    <div class="e-label">En revisión</div>
                    <div class="e-tag">✓ Listo</div>
                </div>
                <div class="estado-row">
                    <div class="e-dot active"></div>
                    <div class="e-label active">En reparación</div>
                    <div class="e-tag act">Ahora ⚡</div>
                </div>
                <div class="estado-row">
                    <div class="e-dot wait"></div>
                    <div class="e-label" style="opacity:.4">Listo para recoger</div>
                    <div class="e-tag">—</div>
                </div>
                <div class="estado-row">
                    <div class="e-dot wait"></div>
                    <div class="e-label" style="opacity:.4">Entregado</div>
                    <div class="e-tag">—</div>
                </div>

                <div class="hc-footer">
                    <i class="bi bi-shield-check"></i> Se actualiza sin recargar la página
                </div>
                <a href="{{ url('/consulta') }}" class="hc-cta">
                    <i class="bi bi-search me-1"></i> Consultar el estado de mi equipo
                </a>
            </div>

        </div>
    </section>

    {{-- ══════════ SERVICIOS ══════════ --}}
    {{-- ══════════ SERVICIOS ══════════ --}}
    <section class="sec sec-gris" id="servicios">
        <div class="sec-wrap">
            <div class="text-center">
                <div class="tag"><i class="bi bi-tools"></i> Lo que hacemos</div>
                <h2 class="sec-title" style="margin-top:10px;">Nuestros servicios</h2>
                <p class="sec-lead" style="margin:12px auto 0; text-align:center;">
                    Soluciones tecnológicas para hogares y empresas en Juliaca y toda la región de Puno.
                </p>
            </div>

            <div class="srv-grid">

                <div class="srv-card">
                    <div class="srv-icon"><i class="bi bi-windows"></i></div>
                    <div class="srv-name">Formateo e instalación</div>
                    <div class="srv-desc">Formateo completo, instalación de Windows limpio, drivers y programas esenciales.
                        Tu equipo como nuevo.</div>
                    <div class="srv-price"><i class="bi bi-tag"></i> Desde S/. 50</div>
                </div>

                <div class="srv-card">
                    <div class="srv-icon"><i class="bi bi-shield-x"></i></div>
                    <div class="srv-name">Eliminación de virus</div>
                    <div class="srv-desc">Limpieza profunda de virus, malware, spyware y adware. Recupera la velocidad y
                        seguridad de tu equipo.</div>
                    <div class="srv-price"><i class="bi bi-tag"></i> Desde S/. 40</div>
                </div>

                <div class="srv-card">
                    <div class="srv-icon"><i class="bi bi-speedometer2"></i></div>
                    <div class="srv-name">Optimización de sistema</div>
                    <div class="srv-desc">Limpieza de inicio, registro y archivos temporales. Ideal para equipos lentos sin
                        necesidad de formatear.</div>
                    <div class="srv-price"><i class="bi bi-tag"></i> Desde S/. 40</div>
                </div>

                <div class="srv-card">
                    <div class="srv-icon"><i class="bi bi-floppy2"></i></div>
                    <div class="srv-name">Recuperación de archivos</div>
                    <div class="srv-desc">Rescate de fotos, documentos y datos de discos dañados, formateados o con errores
                        del sistema.</div>
                    <div class="srv-price"><i class="bi bi-tag"></i> Desde S/. 80</div>
                </div>

                <div class="srv-card">
                    <div class="srv-icon"><i class="bi bi-tools"></i></div>
                    <div class="srv-name">Mantenimiento preventivo</div>
                    <div class="srv-desc">Limpieza interna, cambio de pasta térmica y revisión de ventiladores para laptops
                        y PCs de escritorio.</div>
                    <div class="srv-price"><i class="bi bi-tag"></i> Desde S/. 45</div>
                </div>

                <div class="srv-card">
                    <div class="srv-icon"><i class="bi bi-display"></i></div>
                    <div class="srv-name">Cambio de pantalla</div>
                    <div class="srv-desc">Reemplazo de display roto o con fallas en laptops. Diagnóstico previo sin costo
                        adicional.</div>
                    <div class="srv-price"><i class="bi bi-tag"></i> Desde S/. 120</div>
                </div>

                <div class="srv-card">
                    <div class="srv-icon"><i class="bi bi-battery-charging"></i></div>
                    <div class="srv-name">Batería y cargador</div>
                    <div class="srv-desc">Cambio de batería desgastada o reparación del puerto y conector de carga.
                        Repuestos garantizados.</div>
                    <div class="srv-price"><i class="bi bi-tag"></i> Desde S/. 80</div>
                </div>

                <div class="srv-card">
                    <div class="srv-icon"><i class="bi bi-cpu"></i></div>
                    <div class="srv-name">Ampliación de RAM y SSD</div>
                    <div class="srv-desc">Instalación de memoria RAM adicional o migración de HDD a SSD. Mejora notoria en
                        rendimiento.</div>
                    <div class="srv-price"><i class="bi bi-tag"></i> Desde S/. 30 (mano de obra)</div>
                </div>

                <div class="srv-card">
                    <div class="srv-icon"><i class="bi bi-motherboard"></i></div>
                    <div class="srv-name">Reparación de placa madre</div>
                    <div class="srv-desc">Diagnóstico avanzado y soldadura de componentes en placas madre de laptops y PCs.
                    </div>
                    <div class="srv-price"><i class="bi bi-tag"></i> Desde S/. 150</div>
                </div>

                <div class="srv-card">
                    <div class="srv-icon"><i class="bi bi-keyboard"></i></div>
                    <div class="srv-name">Cambio de teclado</div>
                    <div class="srv-desc">Reemplazo de teclado dañado o con teclas fallidas en laptops de cualquier marca.
                    </div>
                    <div class="srv-price"><i class="bi bi-tag"></i> Desde S/. 80</div>
                </div>

                <div class="srv-card">
                    <div class="srv-icon"><i class="bi bi-printer"></i></div>
                    <div class="srv-name">Reparación de impresoras</div>
                    <div class="srv-desc">Mantenimiento, limpieza de cabezales, cambio de rodillos y reparación de
                        impresoras de cualquier marca.</div>
                    <div class="srv-price"><i class="bi bi-tag"></i> Desde S/. 35</div>
                </div>

                <div class="srv-card">
                    <div class="srv-icon"><i class="bi bi-wifi"></i></div>
                    <div class="srv-name">Redes y configuración</div>
                    <div class="srv-desc">Configuración de routers, redes WiFi y soporte remoto para hogares y empresas.
                    </div>
                    <div class="srv-price"><i class="bi bi-tag"></i> Desde S/. 50</div>
                </div>

            </div>
        </div>
    </section>
    {{-- ══════════ CONSULTAR EQUIPO ══════════ --}}
    {{-- ══════════ CONSULTAR EQUIPO ══════════ --}}
    {{-- Reemplaza toda la sección <section class="sec c-section" id="consulta"> --}}

    <section class="sec c-section" id="consulta">
        <div class="c-inner">

            {{-- Info izquierda --}}
            <div>
                <div class="tag tag-w"><i class="bi bi-search"></i> Seguimiento en vivo</div>
                <h2 class="sec-title sec-title-w" style="margin-top:10px;">
                    Consulta el estado<br>de tu equipo
                </h2>
                <p class="sec-lead sec-lead-w" style="margin-top:12px;">
                    Ingresa el código de tu ticket y tu celular. Verás el estado actualizado en tiempo real,
                    sin necesidad de llamar a la tienda.
                </p>

                <div style="margin-top:32px;">
                    <div class="paso">
                        <div class="paso-n">1</div>
                        <div>
                            <div class="paso-title">Ingresa tu código</div>
                            <div class="paso-desc">En tu ticket de recepción (ej: NX-2026-0042)</div>
                        </div>
                    </div>
                    <div class="paso">
                        <div class="paso-n">2</div>
                        <div>
                            <div class="paso-title">Confirma tu celular</div>
                            <div class="paso-desc">El número con el que dejaste el equipo</div>
                        </div>
                    </div>
                    <div class="paso">
                        <div class="paso-n">3</div>
                        <div>
                            <div class="paso-title">Ve el estado en tiempo real</div>
                            <div class="paso-desc">Se actualiza automáticamente cada 30 segundos</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Formulario --}}
            <div>
                <div class="fc">
                    <div class="fc-title">
                        <i class="bi bi-search" style="color:var(--naranja);"></i> Consultar mi equipo
                    </div>
                    <div class="fc-sub">Consulta gratuita · Sin registro</div>

                    {{-- FORMULARIO — submit interceptado por JS --}}
                    <div id="hf-form">
                        <label>Código de orden</label>
                        <input id="hf-codigo" type="text" placeholder="NX-2026-0042"
                            style="text-transform:uppercase;letter-spacing:.04em;" autocomplete="off">
                        <label>Número de celular</label>
                        <input id="hf-celular" type="text" placeholder="Ej: 951234567" maxlength="12">
                        <button type="button" id="hf-btn" class="btn-buscar" onclick="HF.buscar()">
                            <i class="bi bi-search"></i>
                            <span id="hf-btn-txt">Consultar estado</span>
                        </button>
                    </div>

                    {{-- Resultado (se inyecta aquí por JS) --}}
                    <div id="hf-resultado"></div>

                    <div class="fc-footer">
                        <i class="bi bi-shield-check"></i> Tu información es privada y segura
                    </div>
                </div>
                <a href="{{ url('/consulta') }}" class="fc-link">
                    Ver página de consulta completa <i class="bi bi-arrow-right"></i>
                </a>
            </div>

        </div>
    </section>

    <style>
        /* ─── Spinner ─── */
        .hf-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, .3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: hfspin .7s linear infinite;
            vertical-align: middle;
            margin-right: 6px
        }

        @keyframes hfspin {
            to {
                transform: rotate(360deg)
            }
        }

        /* ─── Resultado card ─── */
        #hf-resultado {
            animation: hffade .35s ease both
        }

        @keyframes hffade {
            from {
                opacity: 0;
                transform: translateY(6px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        /* ─── Timeline de estados ─── */
        .hf-step {
            display: flex;
            gap: 10px;
            align-items: flex-start
        }

        .hf-step-line {
            display: flex;
            flex-direction: column;
            align-items: center
        }

        .hf-step-dot {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .72rem;
            font-weight: 700;
            flex-shrink: 0;
            color: #fff
        }

        .hf-step-dot.done {
            background: #22c55e
        }

        .hf-step-dot.now {
            background: var(--naranja)
        }

        .hf-step-dot.pend {
            background: rgba(255, 255, 255, .1);
            color: rgba(255, 255, 255, .3)
        }

        .hf-step-connector {
            width: 2px;
            height: 14px;
            background: rgba(255, 255, 255, .08);
            margin: 2px 0
        }

        .hf-step-label {
            padding-bottom: 10px;
            font-size: .84rem;
            font-weight: 600
        }

        .hf-step-label.done {
            color: rgba(255, 255, 255, .8)
        }

        .hf-step-label.now {
            color: var(--naranja-lite)
        }

        .hf-step-label.pend {
            color: rgba(255, 255, 255, .3)
        }

        /* ─── Badge actualización ─── */
        .hf-refresh-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .74rem;
            color: rgba(255, 255, 255, .4);
            margin-top: 10px
        }

        .hf-refresh-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22c55e;
            animation: hfpulse 2s ease-in-out infinite
        }

        @keyframes hfpulse {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .3
            }
        }
    </style>

    <script>
        const HF = (() => {
            const CSRF = '{{ csrf_token() }}';
            const URL = '{{ url('/chatbot/buscar') }}';

            const ESTADOS = ['recibido', 'en_revision', 'esperando_aprobacion', 'en_reparacion', 'listo',
                'entregado'
            ];
            const LABELS = ['Recibido', 'En revisión', 'Esp. aprobación', 'En reparación', 'Listo ✅', 'Entregado'];

            let timer = null; // intervalo de auto-refresh
            let ultimoBusq = null; // { codigo, celular } de la última búsqueda exitosa

            /* ── Buscar ── */
            async function buscar() {
                const codigo = document.getElementById('hf-codigo').value.trim().toUpperCase();
                const celular = document.getElementById('hf-celular').value.trim();
                if (!codigo || !celular) {
                    shake();
                    return;
                }

                setBtnLoading(true);
                clearAutoRefresh();

                try {
                    const d = await llamar(codigo, celular);
                    if (d.encontrado) {
                        ultimoBusq = {
                            codigo,
                            celular
                        };
                        renderOrden(d.orden, false);
                        iniciarAutoRefresh();
                    } else if (d.motivo === 'excepcion') {
                        renderError(`Error interno: ${d.error}`);
                    } else {
                        renderNoEncontrado(codigo);
                    }
                } catch (e) {
                    renderError('No se pudo conectar con el servidor. Intenta de nuevo.');
                }

                setBtnLoading(false);
                // Scroll suave solo al resultado, NO al inicio de la página
                setTimeout(() => {
                    document.getElementById('hf-resultado')
                        .scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                }, 100);
            }

            /* ── Llamar al backend ── */
            async function llamar(codigo, celular) {
                const r = await fetch(URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        codigo,
                        celular
                    })
                });
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            }

            /* ── Auto-refresh cada 30s ── */
            function iniciarAutoRefresh() {
                clearAutoRefresh();
                let seg = 30;
                actualizarContador(seg);

                timer = setInterval(async () => {
                    seg--;
                    actualizarContador(seg);
                    if (seg <= 0) {
                        seg = 30;
                        if (ultimoBusq) {
                            try {
                                const d = await llamar(ultimoBusq.codigo, ultimoBusq.celular);
                                if (d.encontrado) renderOrden(d.orden, true);
                            } catch {}
                        }
                    }
                }, 1000);
            }

            function clearAutoRefresh() {
                if (timer) {
                    clearInterval(timer);
                    timer = null;
                }
            }

            function actualizarContador(seg) {
                const el = document.getElementById('hf-refresh-counter');
                if (el) el.textContent = `Próxima actualización en ${seg}s`;
            }

            /* ── Render orden ── */
            function renderOrden(o, esRefresh) {
                const posA = ESTADOS.indexOf(o.estado);

                const timeline = ESTADOS.map((est, i) => {
                    const tipo = i < posA ? 'done' : (i === posA ? 'now' : 'pend');
                    const ico = tipo === 'done' ? '✓' : (tipo === 'now' ? '◉' : '○');
                    const esUltimo = i === ESTADOS.length - 1;
                    return `
                <div class="hf-step">
                    <div class="hf-step-line">
                        <div class="hf-step-dot ${tipo}">${ico}</div>
                        ${!esUltimo ? '<div class="hf-step-connector"></div>' : ''}
                    </div>
                    <div class="hf-step-label ${tipo}" style="${!esUltimo?'':'padding-bottom:0'}">
                        ${LABELS[i]}
                        ${tipo==='now' ? '<span style="font-size:.68rem;background:rgba(240,120,32,.2);color:var(--naranja-lite);border-radius:99px;padding:1px 7px;margin-left:4px;">AHORA</span>' : ''}
                    </div>
                </div>`;
                }).join('');

                const foto = o.foto_url ?
                    `<img src="${o.foto_url}" alt="Foto equipo"
                    style="width:100%;max-width:260px;border-radius:9px;object-fit:cover;
                           margin-bottom:14px;border:1px solid rgba(255,255,255,.09);"
                    onerror="this.style.display='none'">` :
                    '';

                const monto = o.total_final ?? o.costo_estimado;
                const montoHtml = monto ?
                    `<div style="margin-top:10px;font-size:.85rem;color:rgba(255,255,255,.7);">
                   💰 <strong style="color:#fff;">${o.total_final ? 'Total' : 'Estimado'}:</strong>
                   S/. ${monto}
                   <span style="font-size:.72rem;padding:2px 8px;border-radius:99px;margin-left:4px;
                          background:${o.estado_pago==='pagado'?'rgba(34,197,94,.2)':'rgba(249,115,22,.2)'};
                          color:${o.estado_pago==='pagado'?'#86efac':'#fdba74'}">
                       ${o.estado_pago==='pagado'?'✅ Pagado':'⏳ Pendiente'}
                   </span>
               </div>` :
                    '';

                const flash = esRefresh ? 'animation:hffade .4s ease both' : '';

                document.getElementById('hf-resultado').innerHTML = `
            <div style="margin-top:20px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);
                        border-radius:12px;padding:20px;${flash}">
                <div style="font-size:.78rem;color:rgba(255,255,255,.4);margin-bottom:10px;">✅ Orden encontrada</div>
                <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:12px;">
                    <span style="font-family:var(--ff-display);font-weight:800;color:#fff;font-size:1.05rem;">${o.codigo}</span>
                    <span style="font-size:.74rem;background:rgba(240,120,32,.2);color:var(--naranja-lite);border-radius:99px;padding:2px 10px;font-weight:700;">${LABELS[posA]}</span>
                </div>
                <div style="font-size:.87rem;color:rgba(255,255,255,.65);margin-bottom:14px;">
                    <strong style="color:#fff;">${o.cliente_nombre}</strong> · ${o.tipo_equipo}
                    ${o.marca ? `— ${o.marca} ${o.modelo}` : ''}
                </div>
                ${foto}
                ${timeline}
                ${montoHtml}
                <a href="{{ url('/consulta') }}"
                   style="display:block;margin-top:14px;text-align:center;color:var(--naranja-lite);font-size:.82rem;text-decoration:none;">
                    Ver detalle completo <i class="bi bi-arrow-right"></i>
                </a>
                <div class="hf-refresh-badge">
                    <span class="hf-refresh-dot"></span>
                    <span id="hf-refresh-counter">Próxima actualización en 30s</span>
                </div>
            </div>`;
            }

            /* ── Render no encontrado ── */
            function renderNoEncontrado(codigo) {
                document.getElementById('hf-resultado').innerHTML = `
            <div style="margin-top:18px;background:rgba(220,38,38,.1);border:1px solid rgba(220,38,38,.2);
                        border-radius:10px;padding:16px;color:#fca5a5;">
                <div style="font-weight:700;margin-bottom:8px;display:flex;align-items:center;gap:7px;">
                    <i class="bi bi-exclamation-circle"></i> No encontramos tu orden
                </div>
                <p style="font-size:.85rem;margin-bottom:12px;opacity:.85;">
                    Verifica el código <strong>${codigo}</strong> y el celular. Si el problema persiste, escríbenos.
                </p>
                <a href="https://wa.me/51986560904?text=Hola,%20necesito%20ayuda%20con%20mi%20orden%20${codigo}"
                   target="_blank"
                   style="display:inline-flex;align-items:center;gap:6px;background:#25d366;color:#fff;
                          border-radius:8px;padding:7px 14px;font-size:.83rem;font-weight:700;text-decoration:none;">
                    <i class="bi bi-whatsapp"></i> Ayuda por WhatsApp
                </a>
            </div>`;
            }

            /* ── Render error ── */
            function renderError(msg) {
                document.getElementById('hf-resultado').innerHTML = `
            <div style="margin-top:16px;background:rgba(220,38,38,.1);border:1px solid rgba(220,38,38,.2);
                        border-radius:10px;padding:14px;color:#fca5a5;font-size:.85rem;">
                <i class="bi bi-exclamation-triangle"></i> ${msg}
            </div>`;
            }

            /* ── Botón loading ── */
            function setBtnLoading(v) {
                const btn = document.getElementById('hf-btn');
                const txt = document.getElementById('hf-btn-txt');
                btn.disabled = v;
                txt.innerHTML = v ?
                    '<span class="hf-spinner"></span>Buscando...' :
                    '<i class="bi bi-search"></i> Consultar estado';
            }

            /* ── Shake si campos vacíos ── */
            function shake() {
                const form = document.getElementById('hf-form');
                form.style.animation = 'none';
                form.offsetHeight;
                form.style.animation = 'hfshake .35s ease';
            }

            /* Enter en los inputs */
            document.addEventListener('DOMContentLoaded', () => {
                ['hf-codigo', 'hf-celular'].forEach(id => {
                    document.getElementById(id)
                        ?.addEventListener('keydown', e => {
                            if (e.key === 'Enter') buscar();
                        });
                });
            });

            return {
                buscar
            };
        })();
    </script>

    <style>
        @keyframes hfshake {

            0%,
            100% {
                transform: translateX(0)
            }

            25% {
                transform: translateX(-6px)
            }

            75% {
                transform: translateX(6px)
            }
        }
    </style>

    {{-- ══════════ NOSOTROS ══════════ --}}
    <section class="sec" id="nosotros">
        <div class="nos-inner">

            {{-- Izquierda --}}
            <div>
                <div class="tag"><i class="bi bi-building"></i> Quiénes somos</div>
                <h2 class="sec-title" style="margin-top:10px;">
                    Grupo Nexos Tiendas,<br>tu aliado tecnológico
                </h2>
                <p class="sec-lead" style="margin-top:14px;">
                    Somos una empresa juliaquena dedicada a hacer más sencillo el día a día de las personas a través de
                    soluciones tecnológicas con responsabilidad, calidad y compromiso.
                </p>

                <div style="margin-top:28px;">
                    <div class="mv-card" style="background:linear-gradient(135deg,var(--naranja),#b84a00);">
                        <div class="mv-label">Misión</div>
                        <p class="mv-text">Hacer más sencillo el día a día de las personas, brindando soluciones que
                            generen valor con responsabilidad, calidad y compromiso.</p>
                    </div>
                    <div class="mv-card" style="background:var(--negro2);border:1px solid rgba(255,255,255,.07);">
                        <div class="mv-label" style="color:var(--naranja-lite);">Visión</div>
                        <p class="mv-text" style="color:rgba(255,255,255,.8);">Ser una corporación líder a nivel nacional,
                            reconocida por impulsar el acceso a tecnología de última generación.</p>
                    </div>
                </div>
            </div>

            {{-- Derecha --}}
            <div>
                <div class="valor">
                    <div class="valor-ico"><i class="bi bi-shield-check"></i></div>
                    <div>
                        <div class="valor-name">Responsabilidad</div>
                        <div class="valor-desc">Cumplimos nuestros compromisos. Servicio confiable dentro de los plazos
                            acordados.</div>
                    </div>
                </div>
                <div class="valor">
                    <div class="valor-ico"><i class="bi bi-eye"></i></div>
                    <div>
                        <div class="valor-name">Honestidad</div>
                        <div class="valor-desc">Transparencia total en venta y servicio técnico. Te decimos exactamente qué
                            tiene tu equipo.</div>
                    </div>
                </div>
                <div class="valor">
                    <div class="valor-ico"><i class="bi bi-star"></i></div>
                    <div>
                        <div class="valor-name">Compromiso</div>
                        <div class="valor-desc">Nos comprometemos con el resultado, no solo con el intento. Tu satisfacción
                            es nuestra meta.</div>
                    </div>
                </div>
                <div class="valor">
                    <div class="valor-ico"><i class="bi bi-lightning-charge"></i></div>
                    <div>
                        <div class="valor-name">Innovación</div>
                        <div class="valor-desc">Siempre actualizados para ofrecerte las mejores soluciones del mercado
                            tecnológico.</div>
                    </div>
                </div>

                <div class="info-box" style="margin-top:24px;">
                    <h5><i class="bi bi-geo-alt-fill" style="color:var(--naranja);margin-right:7px;"></i>Dónde
                        encontrarnos</h5>
                    <div class="irow"><i class="bi bi-map-fill"></i><span>Jr. Ayaviri Nro. 741, Plaza San José,
                            Juliaca</span></div>
                    <div class="irow"><i class="bi bi-whatsapp wsp"></i><a href="https://wa.me/51986560904">+51 986 560
                            904</a></div>
                    <div class="irow"><i class="bi bi-clock-fill"></i><span>Lun–Vie 9am–7pm · Sáb 9am–2pm · Dom
                            cerrado</span></div>
                    <div class="irow"><i class="bi bi-envelope-fill"></i><a
                            href="mailto:gerencia@nexostiendas.com.pe">gerencia@nexostiendas.com.pe</a></div>
                </div>
            </div>

        </div>
    </section>

    {{-- ══════════ CTA FINAL ══════════ --}}
    <section class="cta-section">
        <h2>¿Tu equipo necesita reparación?</h2>
        <p>Tráelo a Nexos. Lo evaluamos sin costo y te damos un presupuesto honesto.</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="https://wa.me/51986560904?text=Hola,%20quiero%20llevar%20mi%20equipo%20a%20reparar" target="_blank"
                class="btn-wsp">
                <i class="bi bi-whatsapp"></i> Escribir por WhatsApp
            </a>
            <a href="{{ url('/consulta') }}" class="btn-outline-w">
                <i class="bi bi-search"></i> Consultar mi orden
            </a>
        </div>
    </section>

@endsection
