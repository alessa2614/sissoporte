<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Nexos Tiendas — Servicio Técnico Juliaca')</title>
    <meta name="description" content="Venta de tecnología y servicio técnico especializado en Juliaca, Puno.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --naranja: #f07820;
            --naranja-osc: #c85c0a;
            --naranja-lite: #ffb25a;
            --negro: #0e0d0d;
            --negro2: #1a1410;
            --texto: #1c1917;
            --muted: #78716c;
            --gris: #f7f5f3;
            --borde: #e7e3de;
            --ff-display: 'Syne', sans-serif;
            --ff-body: 'DM Sans', sans-serif;
            --ease: cubic-bezier(.4, 0, .2, 1);
            --t: .22s;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--ff-body);
            color: var(--texto);
            background: #fff;
            overflow-x: hidden;
            line-height: 1.65;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── NAVBAR ─────────────────────────────────────────── */
        #nav {
            position: fixed;
            inset: 0 0 auto 0;
            z-index: 900;
            height: 68px;
            transition: background var(--t) var(--ease), box-shadow var(--t);
        }

        #nav.solid {
            background: rgba(14, 13, 13, .96);
            backdrop-filter: blur(20px);
            box-shadow: 0 1px 0 rgba(255, 255, 255, .06);
        }

        .nav-wrap {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            height: 68px;
            padding: 0 28px;
            gap: 40px;
        }

        /* Logo */
        .nav-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            flex-shrink: 0;
        }

        .nav-logo img {
            height: 36px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        /* Links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 2px;
            list-style: none;
            flex: 1;
        }

        .nav-links a {
            color: rgba(255, 255, 255, .65);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            padding: 7px 13px;
            border-radius: 7px;
            transition: all var(--t);
        }

        .nav-links a:hover {
            color: #fff;
            background: rgba(255, 255, 255, .08);
        }

        .nav-links a.on {
            color: var(--naranja-lite);
        }

        /* Acciones */
        .nav-end {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .btn-consultar-nav {
            background: var(--naranja);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: .85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background var(--t), transform var(--t), box-shadow var(--t);
        }

        .btn-consultar-nav:hover {
            background: var(--naranja-osc);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(240, 120, 32, .38);
        }

        .btn-login-nav {
            color: rgba(255, 255, 255, .55);
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 8px;
            padding: 7px 15px;
            font-size: .85rem;
            transition: all var(--t);
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-login-nav:hover {
            color: #fff;
            border-color: rgba(255, 255, 255, .35);
            background: rgba(255, 255, 255, .06);
        }

        /* Mobile */
        .nav-burger {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 4px;
        }

        @media (max-width: 860px) {

            .nav-links,
            .nav-end {
                display: none;
            }

            .nav-burger {
                display: flex;
                margin-left: auto;
            }

            .nav-links.open {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                position: fixed;
                inset: 68px 0 0 0;
                background: rgba(14, 13, 13, .98);
                padding: 20px 24px;
                gap: 4px;
                z-index: 899;
            }

            .nav-end.open {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                gap: 8px;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: rgba(14, 13, 13, .98);
                padding: 16px 24px;
                border-top: 1px solid rgba(255, 255, 255, .06);
                z-index: 899;
            }
        }

        /* ─── FOOTER ────────────────────────────────────────── */
        .footer {
            background: var(--negro);
            color: rgba(255, 255, 255, .5);
            padding: 56px 0 28px;
            font-size: .875rem;
            line-height: 1.75;
        }

        .footer-wrap {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 28px;
        }

        .footer-logo img {
            height: 34px;
            width: auto;
            object-fit: contain;
            margin-bottom: 14px;
            display: block;
        }

        .f-h {
            color: #fff;
            font-weight: 600;
            font-size: .875rem;
            margin-bottom: 14px;
            letter-spacing: .03em;
        }

        .footer ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .footer a {
            color: rgba(255, 255, 255, .45);
            text-decoration: none;
            transition: color var(--t);
        }

        .footer a:hover {
            color: var(--naranja-lite);
        }

        .f-hr {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, .07);
            margin: 36px 0 20px;
        }

        .f-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .f-bottom a {
            color: var(--naranja-lite);
            text-decoration: none;
            font-size: .82rem;
        }

        .f-social {
            display: flex;
            gap: 8px;
            margin-top: 14px;
        }

        .f-social a {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: rgba(255, 255, 255, .06);
            color: rgba(255, 255, 255, .55);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .95rem;
            text-decoration: none;
            transition: all var(--t);
        }

        .f-social a:hover {
            background: var(--naranja);
            color: #fff;
        }

        .f-contact-row {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin-bottom: 10px;
            font-size: .875rem;
        }

        .f-contact-row i {
            color: var(--naranja);
            flex-shrink: 0;
            margin-top: 2px;
        }

        .f-contact-row i.wsp {
            color: #25d366;
        }

        /* ─── CONTENIDO ─────────────────────────────────────── */
        .page-body {
            padding-top: 68px;
        }
    </style>
    @stack('estilos')
</head>

<body>

    {{-- NAVBAR --}}
    <nav id="nav">
        <div class="nav-wrap">
            <a href="{{ url('/') }}" class="nav-logo">
                <img src="{{ asset('assets/compiled/imagen/image.png') }}" alt="Nexos Tiendas">
            </a>

            <ul class="nav-links" id="navLinks">
                <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'on' : '' }}">Inicio</a></li>
                <li><a href="{{ url('/#servicios') }}">Servicios</a></li>
                <li><a href="{{ url('/#nosotros') }}">Nosotros</a></li>
                <li><a href="{{ url('/consulta') }}" class="{{ request()->is('consulta') ? 'on' : '' }}">Consultar
                        equipo</a></li>
            </ul>

            <div class="nav-end" id="navEnd">
                <a href="{{ url('/consulta') }}" class="btn-consultar-nav">
                    <i class="bi bi-search"></i> Consultar
                </a>
                <a href="{{ route('login') }}" class="btn-login-nav">
                    <i class="bi bi-lock"></i> Personal
                </a>
            </div>

            <button class="nav-burger" id="navBurger"><i class="bi bi-list" id="burgerIcon"></i></button>
        </div>
    </nav>

    {{-- CONTENIDO --}}
    <div class="page-body">@yield('content')</div>

    {{-- FOOTER --}}
    <footer class="footer">
        <div class="footer-wrap">
            <div style="display:grid; grid-template-columns: 2fr 1fr 1fr 2fr; gap: 40px;">

                <div>
                    <div class="footer-logo">
                        <img src="{{ asset('assets/compiled/imagen/image.png') }}" alt="Nexos Tiendas">
                    </div>
                    <p style="max-width:280px; margin-bottom:0;">Venta de tecnología y servicio técnico especializado en
                        Juliaca, Puno. Más de 6 años en el mercado.</p>
                    <div class="f-social">
                        <a href="https://wa.me/51986560904" target="_blank" title="WhatsApp"><i
                                class="bi bi-whatsapp"></i></a>
                        <a href="https://web.facebook.com/NexosTiendasOficial/?locale=es_LA&_rdc=1&_rdr#"
                            target="_blank" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" title="TikTok"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>

                <div>
                    <p class="f-h">Menú</p>
                    <ul>
                        <li><a href="{{ url('/') }}">Inicio</a></li>
                        <li><a href="{{ url('/#servicios') }}">Servicios</a></li>
                        <li><a href="{{ url('/#nosotros') }}">Nosotros</a></li>
                        <li><a href="{{ url('/consulta') }}">Consultar equipo</a></li>
                        <li><a href="{{ route('login') }}">Acceso personal</a></li>
                    </ul>
                </div>

                <div>
                    <p class="f-h">Servicios</p>
                    <ul>
                        <li>Formateo e instalación</li>
                        <li>Eliminación de virus</li>
                        <li>Mantenimiento preventivo</li>
                        <li>Recuperación de archivos</li>
                        <li>Cambio de pantalla</li>
                        <li>Batería y cargador</li>
                        <li>Ampliación RAM y SSD</li>
                        <li>Reparación de placa madre</li>
                        <li>Reparación de impresoras</li>
                        <li>Redes y configuración</li>
                    </ul>
                </div>

                <div>
                    <p class="f-h">Contacto</p>
                    <div class="f-contact-row"><i class="bi bi-geo-alt-fill"></i><span>Jr. Ayaviri Nro. 741, Plaza San
                            José, Juliaca, San Román, Puno</span></div>
                    <div class="f-contact-row"><i class="bi bi-whatsapp wsp"></i><a href="https://wa.me/51986560904">+51
                            986 560 904</a></div>
                    <div class="f-contact-row"><i class="bi bi-envelope-fill"></i><a
                            href="mailto:gerencia@nexostiendas.com.pe">gerencia@nexostiendas.com.pe</a></div>
                    <div class="f-contact-row"><i class="bi bi-clock-fill"></i><span>Lun–Vie 9am–7pm · Sáb
                            9am–2pm</span></div>
                </div>
            </div>

            <div class="f-hr"></div>
            <div class="f-bottom">
                <span style="font-size:.82rem;">© {{ date('Y') }} <strong
                        style="color:rgba(255,255,255,.65);">GRUPO NEXOS TIENDAS E.I.R.L.</strong> — Juliaca,
                    Puno</span>
                <a href="{{ url('/consulta') }}"><i class="bi bi-search me-1"></i>Consultar mi equipo</a>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll
        const nav = document.getElementById('nav');
        const solidOnScroll = () => nav.classList.toggle('solid', window.scrollY > 20);
        window.addEventListener('scroll', solidOnScroll, {
            passive: true
        });
        @if (!request()->is('/'))
            nav.classList.add('solid');
        @endif

        // Mobile menu
        const burger = document.getElementById('navBurger');
        const links = document.getElementById('navLinks');
        const end = document.getElementById('navEnd');
        const icon = document.getElementById('burgerIcon');
        let open = false;
        burger.addEventListener('click', () => {
            open = !open;
            links.classList.toggle('open', open);
            end.classList.toggle('open', open);
            icon.className = open ? 'bi bi-x-lg' : 'bi bi-list';
        });
        links.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
            open = false;
            links.classList.remove('open');
            end.classList.remove('open');
            icon.className = 'bi bi-list';
        }));

        // Footer grid responsive
        const fgrid = document.querySelector('.footer-wrap > div[style*="grid"]');
        if (fgrid) {
            const resizeFoot = () => {
                fgrid.style.gridTemplateColumns = window.innerWidth < 768 ? '1fr' : window.innerWidth < 1024 ?
                    '1fr 1fr' : '2fr 1fr 1fr 2fr';
            };
            window.addEventListener('resize', resizeFoot);
            resizeFoot();
        }
    </script>
    @include('web.partials.chatbot')
    @stack('scripts')
</body>

</html>
