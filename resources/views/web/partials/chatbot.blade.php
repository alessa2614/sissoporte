{{-- resources/views/web/partials/chatbot.blade.php --}}
<style>
    :root {
        --cbn: #f07820;
        --cbd: #c85c0a;
        --cbl: #ffb25a;
        --cbk: #111010;
        --cbk2: #1c1a17
    }

    #cb-btn {
        position: fixed;
        bottom: 26px;
        right: 26px;
        z-index: 9000;
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--cbn), var(--cbd));
        border: none;
        cursor: pointer;
        color: #fff;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 28px rgba(240, 120, 32, .6);
        transition: transform .2s
    }

    #cb-btn:hover {
        transform: scale(1.1)
    }

    #cb-btn::before {
        content: '';
        position: absolute;
        inset: -5px;
        border-radius: 50%;
        border: 2.5px solid rgba(240, 120, 32, .35);
        animation: cbpulse 2.8s ease-out infinite
    }

    @keyframes cbpulse {
        0% {
            transform: scale(1);
            opacity: 1
        }

        100% {
            transform: scale(1.7);
            opacity: 0
        }
    }

    #cb-btn .ico-open {
        display: flex
    }

    #cb-btn .ico-close {
        display: none
    }

    #cb-btn.open .ico-open {
        display: none
    }

    #cb-btn.open .ico-close {
        display: flex
    }

    /* ── ROBOT FLOTANTE ── */
    #cb-robot {
        position: fixed;
        bottom: 158px;
        right: 20px;
        z-index: 9001;
        cursor: pointer;
        animation: robotflot 2.6s ease-in-out infinite;
        filter: drop-shadow(0 6px 14px rgba(240, 120, 32, .55));
        opacity: 1;
        transition: opacity .3s
    }

    #cb-robot.hidden {
        opacity: 0 !important;
        pointer-events: none !important;
        animation: none !important
    }

    @keyframes robotflot {

        0%,
        100% {
            transform: translateY(0)
        }

        50% {
            transform: translateY(-7px)
        }
    }

    @keyframes blink {

        0%,
        88%,
        100% {
            transform: scaleY(1)
        }

        93% {
            transform: scaleY(.08)
        }
    }

    .rb-eye {
        animation: blink 3.8s ease-in-out infinite
    }

    .rb-eye2 {
        animation: blink 3.8s ease-in-out infinite;
        animation-delay: .12s
    }

    /* ── BADGE ── */
    #cb-badge {
        position: fixed;
        bottom: 96px;
        right: 18px;
        z-index: 8999;
        background: var(--cbk);
        color: #fff;
        font-size: .82rem;
        padding: 9px 16px;
        border-radius: 12px;
        white-space: nowrap;
        cursor: pointer;
        border: 1px solid rgba(255, 255, 255, .12);
        box-shadow: 0 6px 22px rgba(0, 0, 0, .45);
        opacity: 1;
        transition: opacity .4s
    }

    #cb-badge::after {
        content: '';
        position: absolute;
        bottom: -7px;
        right: 20px;
        border: 7px solid transparent;
        border-top-color: var(--cbk);
        border-bottom: none
    }

    #cb-badge.hidden {
        opacity: 0 !important;
        pointer-events: none !important
    }

    #cb-win {
        position: fixed;
        bottom: 96px;
        right: 26px;
        z-index: 8998;
        width: 360px;
        height: 560px;
        background: var(--cbk2);
        border: 1px solid rgba(255, 255, 255, .1);
        border-radius: 20px;
        box-shadow: 0 28px 72px rgba(0, 0, 0, .65);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transform: scale(.88) translateY(16px);
        opacity: 0;
        pointer-events: none;
        transition: transform .28s cubic-bezier(.4, 0, .2, 1), opacity .24s ease
    }

    #cb-win.open {
        transform: scale(1) translateY(0);
        opacity: 1;
        pointer-events: all
    }

    .cb-head {
        background: var(--cbk);
        padding: 13px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, .07);
        flex-shrink: 0
    }

    .cb-ava {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--cbn), #903400);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 800;
        color: #fff;
        flex-shrink: 0;
        position: relative
    }

    .cb-ava::after {
        content: '';
        position: absolute;
        bottom: 1px;
        right: 1px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #22c55e;
        border: 2px solid var(--cbk)
    }

    .cb-head-info {
        flex: 1
    }

    .cb-head-name {
        font-size: .92rem;
        font-weight: 700;
        color: #fff
    }

    .cb-head-sub {
        font-size: .72rem;
        color: rgba(255, 255, 255, .42);
        margin-top: 1px
    }

    .cb-close {
        background: none;
        border: none;
        color: rgba(255, 255, 255, .4);
        font-size: 1.1rem;
        cursor: pointer;
        padding: 4px
    }

    .cb-close:hover {
        color: #fff
    }

    .cb-msgs {
        flex: 1;
        overflow-y: auto;
        padding: 14px 13px 8px;
        display: flex;
        flex-direction: column;
        gap: 9px;
        scroll-behavior: smooth
    }

    .cb-msgs::-webkit-scrollbar {
        width: 3px
    }

    .cb-msgs::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, .1);
        border-radius: 3px
    }

    .cb-msg {
        display: flex;
        gap: 7px;
        align-items: flex-end;
        animation: cbfade .28s ease both
    }

    @keyframes cbfade {
        from {
            opacity: 0;
            transform: translateY(5px)
        }

        to {
            opacity: 1;
            transform: translateY(0)
        }
    }

    .cb-msg.bot {
        justify-content: flex-start
    }

    .cb-msg.user {
        justify-content: flex-end
    }

    .cb-bbl {
        max-width: 86%;
        padding: 10px 13px;
        border-radius: 14px;
        font-size: .86rem;
        line-height: 1.58;
        word-break: break-word
    }

    .cb-msg.bot .cb-bbl {
        background: rgba(255, 255, 255, .09);
        border: 1px solid rgba(255, 255, 255, .07);
        color: rgba(255, 255, 255, .88);
        border-bottom-left-radius: 4px
    }

    .cb-msg.user .cb-bbl {
        background: var(--cbn);
        color: #fff;
        border-bottom-right-radius: 4px
    }

    .cb-bbl a {
        color: var(--cbl);
        text-decoration: underline
    }

    .cb-bbl strong {
        color: #fff
    }

    .cb-mini {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--cbn), #903400);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .68rem;
        font-weight: 800;
        color: #fff;
        flex-shrink: 0
    }

    .cb-typing {
        display: flex;
        gap: 4px;
        padding: 4px 2px
    }

    .cb-typing span {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .45);
        animation: cbdot .9s ease-in-out infinite
    }

    .cb-typing span:nth-child(2) {
        animation-delay: .16s
    }

    .cb-typing span:nth-child(3) {
        animation-delay: .32s
    }

    @keyframes cbdot {

        0%,
        80%,
        100% {
            transform: scale(.6);
            opacity: .3
        }

        40% {
            transform: scale(1);
            opacity: 1
        }
    }

    .cb-opts-burbuja {
        display: flex;
        flex-direction: column;
        gap: 5px;
        padding-left: 33px;
        animation: cbfade .3s ease both
    }

    .cb-opts-burbuja.desactivado .cb-opt {
        opacity: .4;
        pointer-events: none;
        cursor: default
    }

    .cb-opt {
        background: rgba(255, 255, 255, .06);
        border: 1px solid rgba(255, 255, 255, .1);
        color: rgba(255, 255, 255, .82);
        font-size: .82rem;
        font-weight: 500;
        border-radius: 10px;
        padding: 8px 12px;
        cursor: pointer;
        text-align: left;
        transition: all .15s;
        font-family: inherit;
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%
    }

    .cb-opt:hover {
        background: rgba(240, 120, 32, .15);
        border-color: rgba(240, 120, 32, .3);
        color: #fff
    }

    .cb-opt.naranja {
        background: rgba(240, 120, 32, .12);
        border-color: rgba(240, 120, 32, .28);
        color: #ffb060
    }

    .cb-opt.naranja:hover {
        background: rgba(240, 120, 32, .25)
    }

    .cb-opt.wsp {
        background: rgba(37, 211, 102, .1);
        border-color: rgba(37, 211, 102, .28);
        color: #86efac
    }

    .cb-opt.wsp:hover {
        background: rgba(37, 211, 102, .2)
    }

    .cb-opt.mini {
        font-size: .75rem;
        padding: 5px 10px;
        opacity: .6
    }

    .cb-opt.mini:hover {
        opacity: 1
    }

    .cb-footer {
        display: flex;
        gap: 8px;
        align-items: center;
        padding: 10px 12px;
        border-top: 1px solid rgba(255, 255, 255, .07);
        background: var(--cbk);
        flex-shrink: 0
    }

    #cbInp {
        flex: 1;
        background: rgba(255, 255, 255, .07);
        border: 1px solid rgba(255, 255, 255, .11);
        border-radius: 99px;
        color: #fff;
        padding: 9px 15px;
        font-size: .86rem;
        font-family: inherit;
        outline: none;
        transition: border-color .2s
    }

    #cbInp::placeholder {
        color: rgba(255, 255, 255, .3)
    }

    #cbInp:focus {
        border-color: var(--cbn)
    }

    #cbSend {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--cbn);
        border: none;
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background .18s, transform .15s;
        flex-shrink: 0
    }

    #cbSend:hover {
        background: var(--cbd);
        transform: scale(1.08)
    }

    .e-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border-radius: 99px;
        padding: 3px 10px;
        font-size: .77rem;
        font-weight: 700
    }

    .e-pill.recibido {
        background: rgba(59, 130, 246, .2);
        color: #93c5fd
    }

    .e-pill.revision {
        background: rgba(234, 179, 8, .2);
        color: #fde047
    }

    .e-pill.espera {
        background: rgba(249, 115, 22, .2);
        color: #fdba74
    }

    .e-pill.reparacion {
        background: rgba(239, 68, 68, .2);
        color: #fca5a5
    }

    .e-pill.listo {
        background: rgba(34, 197, 94, .2);
        color: #86efac
    }

    .e-pill.entregado {
        background: rgba(107, 114, 128, .2);
        color: #d1d5db
    }

    .cb-foot {
        text-align: center;
        font-size: .66rem;
        color: rgba(255, 255, 255, .15);
        padding: 3px 0 6px;
        flex-shrink: 0
    }

    @media(max-width:480px) {
        #cb-win {
            width: calc(100vw - 14px);
            right: 7px;
            bottom: 88px
        }

        #cb-btn {
            bottom: 18px;
            right: 18px
        }

        #cb-robot {
            right: 14px
        }
    }
</style>

{{-- ROBOT FLOTANTE --}}
<div id="cb-robot" onclick="CB.abrir()" title="¿En qué te ayudo?">
    <svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="26" cy="50" rx="12" ry="3" fill="rgba(0,0,0,.25)" />
        <line x1="26" y1="3" x2="26" y2="9" stroke="#f07820" stroke-width="2.2"
            stroke-linecap="round" />
        <circle cx="26" cy="2.5" r="2.5" fill="#f07820" />
        <rect x="8" y="9" width="36" height="24" rx="7" fill="#f07820" />
        <rect x="10" y="11" width="32" height="20" rx="5" fill="#1c1208" />
        <rect class="rb-eye" x="13" y="16" width="9" height="9" rx="2.5" fill="#f07820" />
        <rect x="15" y="18" width="3" height="3" rx="1" fill="#fff" opacity=".9" />
        <rect class="rb-eye2" x="30" y="16" width="9" height="9" rx="2.5" fill="#f07820" />
        <rect x="32" y="18" width="3" height="3" rx="1" fill="#fff" opacity=".9" />
        <rect x="17" y="28" width="18" height="3" rx="1.5" fill="#f07820" opacity=".7" />
        <rect x="20" y="28" width="4" height="3" rx="1" fill="#ffb25a" opacity=".6" />
        <rect x="28" y="28" width="4" height="3" rx="1" fill="#ffb25a" opacity=".6" />
        <rect x="4" y="15" width="4" height="10" rx="2" fill="#f07820" opacity=".8" />
        <rect x="44" y="15" width="4" height="10" rx="2" fill="#f07820" opacity=".8" />
        <rect x="16" y="33" width="20" height="13" rx="4" fill="#f07820" opacity=".25" />
        <rect x="18" y="35" width="16" height="9" rx="3" fill="#f07820" opacity=".15" stroke="#f07820"
            stroke-width="1.2" />
        <rect x="22" y="37" width="8" height="5" rx="1.5" fill="#f07820" opacity=".35" />
    </svg>
</div>

<div id="cb-badge" onclick="CB.abrir()">💬 &nbsp;¿En qué te puedo ayudar?</div>

<button id="cb-btn" onclick="CB.toggle()" title="Asistente Nexos">
    <span class="ico-open"><i class="bi bi-chat-dots-fill"></i></span>
    <span class="ico-close"><i class="bi bi-x-lg"></i></span>
</button>

<div id="cb-win">
    <div class="cb-head">
        <div class="cb-ava">N</div>
        <div class="cb-head-info">
            <div class="cb-head-name">Nexos Asistente</div>
            <div class="cb-head-sub">En línea · Siempre disponible</div>
        </div>
        <button class="cb-close" onclick="CB.cerrar()"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="cb-msgs" id="cbMsgs"></div>
    <div class="cb-footer">
        <input id="cbInp" type="text" placeholder="Escribe un mensaje..." maxlength="200" autocomplete="off"
            onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();CB.enviar()}">
        <button id="cbSend" onclick="CB.enviar()"><i class="bi bi-send-fill"></i></button>
    </div>
    <div class="cb-foot">Nexos Tiendas · Juliaca, Puno</div>
</div>

<script>
    const CB = (() => {
        'use strict';

        const URL_BUSCAR = '{{ url('/chatbot/buscar') }}';
        const URL_SERVICIOS = '{{ url('/chatbot/servicios') }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';

        let abierto = false,
            badgeOculto = false;
        let paso = null;
        let datos = {
            codigo: '',
            celular: ''
        };
        let bdCache = null;
        let optsActivos = null;

        const K = {
            saludar: ['hola', 'buenas', 'buenos', 'hi', 'hey', 'buen dia', 'buen día', 'saludos', 'alo'],
            gracias: ['gracias', 'muchas gracias', 'thanks'],
            consultar: ['consultar', 'estado', 'orden', 'ticket', 'codigo', 'código', 'nx-', 'seguimiento',
                'mi equipo'
            ],
            equipos: ['equipos', 'qué equipos', 'que equipos', 'qué reparan', 'que reparan',
                'tipos de equipo'
            ],
            precios: ['precio', 'costo', 'cuánto', 'cuanto', 'cuesta', 'cobran', 'tarifas', 'servicio',
                'servicios', 'reparar'
            ],
            proceso: ['proceso', 'como funciona', 'cómo funciona', 'pasos', 'procedimiento'],
            ubicacion: ['donde', 'dónde', 'ubicacion', 'ubicación', 'dirección', 'direccion', 'ayaviri',
                'local'
            ],
            horario: ['horario', 'hora', 'atienden', 'abren', 'cierran'],
            garantia: ['garantia', 'garantía'],
            marcas: ['marca', 'marcas', 'hp', 'dell', 'lenovo', 'asus', 'samsung', 'iphone', 'mac',
                'xiaomi'],
            wsp: ['whatsapp', 'wsp', 'wasap', 'llamar', 'hablar', 'asesor', 'humano', 'contacto'],
            quien: ['quién eres', 'quien eres', 'qué eres', 'que eres', 'eres bot', 'eres humano',
                'eres ia'],
            comoes: ['cómo estás', 'como estas', 'qué tal', 'que tal', 'todo bien'],
        };
        const tiene = (txt, arr) => arr.some(w => txt.includes(w));

        const $msgs = () => document.getElementById('cbMsgs');
        const $inp = () => document.getElementById('cbInp');
        const $robot = () => document.getElementById('cb-robot');
        const $badge = () => document.getElementById('cb-badge');
        const wait = ms => new Promise(r => setTimeout(r, ms));
        const esc = t => String(t).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

        function addMsg(rol, html, typing = false) {
            const c = $msgs(),
                d = document.createElement('div');
            d.className = 'cb-msg ' + rol;
            d.innerHTML = rol === 'bot' ?
                `<div class="cb-mini">N</div><div class="cb-bbl">${typing ? '<div class="cb-typing"><span></span><span></span><span></span></div>' : html}</div>` :
                `<div class="cb-bbl">${esc(html)}</div>`;
            c.appendChild(d);
            c.scrollTop = c.scrollHeight;
            return d;
        }
        const $tp = () => addMsg('bot', '', true);
        const userSay = txt => addMsg('user', txt);

        async function bot(html, delay = 400) {
            const t = $tp();
            await wait(delay);
            t.remove();
            addMsg('bot', html);
        }

        function mostrarOpts(items) {
            if (optsActivos) {
                optsActivos.classList.add('desactivado');
                optsActivos = null;
            }
            if (!items?.length) return;
            const c = $msgs();
            const bloque = document.createElement('div');
            bloque.className = 'cb-opts-burbuja';
            items.forEach(it => {
                const b = document.createElement('button');
                b.className = 'cb-opt ' + (it.cls || '');
                b.innerHTML = (it.ico ? `<span>${it.ico}</span> ` : '') + it.txt;
                b.onclick = () => {
                    bloque.classList.add('desactivado');
                    optsActivos = null;
                    it.fn();
                };
                bloque.appendChild(b);
            });
            c.appendChild(bloque);
            c.scrollTop = c.scrollHeight;
            optsActivos = bloque;
        }

        function btnMenu() {
            mostrarOpts([{
                ico: '🏠',
                txt: 'Volver al menú principal',
                cls: 'mini',
                fn: () => menu()
            }]);
        }

        /* ══ ABRIR / CERRAR ══ */
        function abrir() {
            abierto = true;
            document.getElementById('cb-win').classList.add('open');
            document.getElementById('cb-btn').classList.add('open');
            $badge().classList.add('hidden');
            $robot().classList.add('hidden');
            badgeOculto = true;
            if (!$msgs().children.length) {
                cargarBD();
                flujoInicio();
            }
            setTimeout(() => $inp().focus(), 300);
        }

        function cerrar() {
            abierto = false;
            document.getElementById('cb-win').classList.remove('open');
            document.getElementById('cb-btn').classList.remove('open');
            $robot().classList.remove('hidden');
        }

        function toggle() {
            abierto ? cerrar() : abrir();
        }

        /* ══ BD ══ */
        async function cargarBD() {
            if (bdCache) return bdCache;
            try {
                const r = await fetch(URL_SERVICIOS, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                if (!r.ok) throw new Error();
                bdCache = await r.json();
            } catch {
                bdCache = {
                    tipos: [],
                    servicios: []
                };
            }
            return bdCache;
        }

        /* ══ ENVIAR ══ */
        async function enviar() {
            const raw = $inp().value.trim();
            if (!raw) return;
            $inp().value = '';
            userSay(raw);
            if (optsActivos) {
                optsActivos.classList.add('desactivado');
                optsActivos = null;
            }
            const txt = raw.toLowerCase();

            if (paso === 'esperando_codigo') {
                paso = null;
                datos.codigo = raw.toUpperCase();
                await flujoPedirCelular();
                return;
            }
            if (paso === 'esperando_celular') {
                paso = null;
                datos.celular = raw;
                await flujoBuscar();
                return;
            }

            if (tiene(txt, K.quien)) {
                await flujoQuienSoy();
                return;
            }
            if (tiene(txt, K.comoes)) {
                await flujoComoEstoy();
                return;
            }
            if (tiene(txt, K.saludar)) {
                await flujoSaludo();
                return;
            }
            if (tiene(txt, K.gracias)) {
                await bot('¡Con gusto! 😊');
                return;
            }
            if (/nx[-\s]?\d{4}[-\s]?\d+/i.test(raw) || tiene(txt, K.consultar)) {
                await flujoConsultar();
                return;
            }
            if (tiene(txt, K.equipos)) {
                await flujoTiposEquipo();
                return;
            }
            if (tiene(txt, K.precios)) {
                await flujoServicios();
                return;
            }
            if (tiene(txt, K.proceso)) {
                await flujoComofunciona();
                return;
            }
            if (tiene(txt, K.ubicacion)) {
                await flujoUbicacion();
                return;
            }
            if (tiene(txt, K.horario)) {
                await flujoHorario();
                return;
            }
            if (tiene(txt, K.garantia)) {
                await flujoGarantia();
                return;
            }
            if (tiene(txt, K.marcas)) {
                await flujoMarcas();
                return;
            }
            if (tiene(txt, K.wsp)) {
                await flujoWsp();
                return;
            }

            await bot('Mmm, no entendí bien 😅 Soy el asistente de Nexos Tiendas. ¿En qué te ayudo?');
            await wait(200);
            menu();
        }

        /* ══ FLUJOS ══ */
        async function flujoInicio() {
            await bot('👋 ¡Hola! Soy el <strong>asistente de Nexos Tiendas</strong>. ¿En qué te ayudo hoy?',
                500);
            menu();
        }

        function menu() {
            mostrarOpts([{
                    ico: '🔍',
                    txt: 'Consultar estado de mi equipo',
                    cls: 'naranja',
                    fn: () => {
                        userSay('Consultar mi equipo');
                        flujoConsultar()
                    }
                },
                {
                    ico: '💰',
                    txt: 'Servicios y precios',
                    fn: () => {
                        userSay('Servicios y precios');
                        flujoMenuServicios()
                    }
                },
                {
                    ico: '📋',
                    txt: '¿Cómo funciona el servicio?',
                    fn: () => {
                        userSay('¿Cómo funciona?');
                        flujoComofunciona()
                    }
                },
                {
                    ico: '❓',
                    txt: 'Otras preguntas',
                    fn: () => {
                        userSay('Otras preguntas');
                        flujoOtras()
                    }
                },
            ]);
        }

        async function flujoSaludo() {
            await bot('¡Hola! 👋 ¿En qué te puedo ayudar?');
            await wait(600);
            menu();
        }
        async function flujoComoEstoy() {
            await bot('¡Todo bien, gracias! 😄 ¿En qué te puedo ayudar?');
            await wait(600);
            menu();
        }

        async function flujoQuienSoy() {
            await bot(
                'Soy el <strong>asistente virtual de Nexos Tiendas</strong> 🤖<br>No soy humano, pero puedo ayudarte con el estado de tu equipo, precios, horarios y más. Para hablar con una persona real escríbenos al WhatsApp.'
                );
            mostrarOpts([{
                    ico: '💬',
                    txt: 'Hablar con alguien',
                    cls: 'wsp',
                    fn: () => flujoWsp()
                },
                {
                    ico: '🏠',
                    txt: 'Volver al menú',
                    cls: 'mini',
                    fn: () => menu()
                },
            ]);
        }

        async function flujoConsultar() {
            await bot('Para ver el estado de tu equipo necesito 2 datos de tu ticket. 🎫');
            await bot('¿Cuál es tu <strong>código de orden</strong>? (ej: <strong>NX-2026-0042</strong>)');
            paso = 'esperando_codigo';
            $inp().placeholder = 'Ej: NX-2026-0042';
            $inp().focus();
        }

        async function flujoPedirCelular() {
            await bot(
                `Código <strong>${datos.codigo}</strong> ✅  Ahora tu <strong>número de celular</strong> registrado:`
                );
            paso = 'esperando_celular';
            $inp().placeholder = 'Ej: 951234567';
            $inp().focus();
        }

        async function flujoBuscar() {
            $inp().placeholder = 'Escribe un mensaje...';
            const t = $tp();
            addMsg('bot', 'Buscando tu orden... 🔎');
            await wait(1300);
            t.remove();
            try {
                const r = await fetch(URL_BUSCAR, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        codigo: datos.codigo,
                        celular: datos.celular
                    })
                });
                if (!r.ok) throw new Error('HTTP ' + r.status);
                const d = await r.json();
                if (d.motivo === 'excepcion') {
                    await bot(`⚠️ Error interno: <em>${esc(d.error)}</em>`);
                    mostrarOpts([{
                            ico: '🔄',
                            txt: 'Intentar de nuevo',
                            cls: 'naranja',
                            fn: () => {
                                datos = {
                                    codigo: '',
                                    celular: ''
                                };
                                flujoConsultar()
                            }
                        },
                        {
                            ico: '🏠',
                            txt: 'Menú',
                            cls: 'mini',
                            fn: () => menu()
                        }
                    ]);
                    return;
                }
                d.encontrado ? await flujoOrdenOk(d.orden) : await flujoNoEncontrada();
            } catch (e) {
                await bot(
                    `❌ No se pudo conectar (${e.message}).<br>Prueba en <a href="{{ url('/consulta') }}">esta página</a> o por WhatsApp.`
                    );
                mostrarOpts([{
                        ico: '🔄',
                        txt: 'Intentar de nuevo',
                        cls: 'naranja',
                        fn: () => {
                            datos = {
                                codigo: '',
                                celular: ''
                            };
                            flujoConsultar()
                        }
                    },
                    {
                        ico: '💬',
                        txt: 'WhatsApp',
                        cls: 'wsp',
                        fn: () => flujoWsp()
                    },
                ]);
            }
        }

        const ESTADOS = {
            recibido: {
                txt: 'Recibido en el taller',
                cls: 'recibido',
                ico: '📥',
                desc: 'Tu equipo está ingresado y en cola para revisión.'
            },
            en_revision: {
                txt: 'En revisión',
                cls: 'revision',
                ico: '🔍',
                desc: 'Un técnico está evaluando el problema de tu equipo.'
            },
            esperando_aprobacion: {
                txt: 'Esperando tu aprobación',
                cls: 'espera',
                ico: '📞',
                desc: 'Tenemos el diagnóstico listo. Te contactaremos pronto.'
            },
            en_reparacion: {
                txt: 'En reparación',
                cls: 'reparacion',
                ico: '🔧',
                desc: 'Nuestro técnico está trabajando activamente en tu equipo.'
            },
            listo: {
                txt: '¡Listo para recoger! ✅',
                cls: 'listo',
                ico: '✅',
                desc: 'Tu equipo está listo. Pasa a recogerlo a Jr. Ayaviri 741.'
            },
            entregado: {
                txt: 'Entregado',
                cls: 'entregado',
                ico: '🎉',
                desc: 'Equipo entregado. Recuerda tu garantía de 30 días.'
            },
        };

        async function flujoOrdenOk(o) {
            const est = ESTADOS[o.estado] ?? {
                txt: o.estado,
                cls: 'entregado',
                ico: '📋',
                desc: ''
            };
            const pago = o.estado_pago === 'pagado';
            let html = `✅ <strong>¡Orden encontrada!</strong><br><br>` +
                `📋 <strong>Código:</strong> ${esc(o.codigo)}<br>` +
                `👤 <strong>Cliente:</strong> ${esc(o.cliente_nombre)}<br>` +
                `💻 <strong>Equipo:</strong> ${esc(o.tipo_equipo)}` +
                (o.marca ? ` — ${esc(o.marca)} ${esc(o.modelo)}` : '');
            if (o.foto_url) html +=
                `<br><img src="${esc(o.foto_url)}" style="width:100%;max-width:220px;border-radius:10px;margin-top:10px;border:2px solid rgba(240,120,32,.3);display:block;" onerror="this.style.display='none'">`;
            await bot(html);
            await wait(600);
            await bot(
                `<strong>Estado actual:</strong><br><span class="e-pill ${est.cls}">${est.ico} ${est.txt}</span><br><br><em style="color:rgba(255,255,255,.6);font-size:.82rem;">${est.desc}</em>`
                );
            const monto = o.total_final ?? o.costo_estimado;
            if (monto) {
                await wait(500);
                await bot(
                    `💰 <strong>${o.total_final ? 'Total final' : 'Estimado'}:</strong> S/. ${monto} <span class="e-pill ${pago ? 'listo' : 'espera'}">${pago ? '✅ Pagado' : '⏳ Pendiente'}</span>`
                    );
            }
            await wait(700);
            mostrarOpts([{
                    ico: '🔄',
                    txt: 'Consultar otra orden',
                    cls: 'naranja',
                    fn: () => {
                        datos = {
                            codigo: '',
                            celular: ''
                        };
                        flujoConsultar()
                    }
                },
                {
                    ico: '💬',
                    txt: 'Hablar con nosotros',
                    cls: 'wsp',
                    fn: () => flujoWsp('Hola, consulto por mi orden ' + o.codigo)
                },
                {
                    ico: '🏠',
                    txt: 'Menú principal',
                    cls: 'mini',
                    fn: () => menu()
                },
            ]);
        }

        async function flujoNoEncontrada() {
            await bot(
                `❌ No encontré la orden <strong>${esc(datos.codigo)}</strong> con ese celular.<br><br>Verifica que el código sea exacto y el celular sea el que usaste al registrarte.`
                );
            await wait(600);
            mostrarOpts([{
                    ico: '🔄',
                    txt: 'Intentar de nuevo',
                    cls: 'naranja',
                    fn: () => {
                        datos = {
                            codigo: '',
                            celular: ''
                        };
                        flujoConsultar()
                    }
                },
                {
                    ico: '💬',
                    txt: 'Pedir ayuda por WhatsApp',
                    cls: 'wsp',
                    fn: () => flujoWsp('Hola, no encuentro mi orden ' + datos.codigo)
                },
                {
                    ico: '🏠',
                    txt: 'Menú principal',
                    cls: 'mini',
                    fn: () => menu()
                },
            ]);
        }

        async function flujoMenuServicios() {
            await bot('¿Qué quieres consultar?');
            await wait(400);
            mostrarOpts([{
                    ico: '🖥️',
                    txt: 'Equipos que reparamos',
                    fn: () => {
                        userSay('Equipos que reparan');
                        flujoTiposEquipo()
                    }
                },
                {
                    ico: '💰',
                    txt: 'Precios de servicios',
                    fn: () => {
                        userSay('Ver precios');
                        flujoServicios()
                    }
                },
                {
                    ico: '🏠',
                    txt: 'Menú principal',
                    cls: 'mini',
                    fn: () => menu()
                },
            ]);
        }

        async function flujoTiposEquipo() {
            const bd = await cargarBD();
            const tipos = bd.tipos ?? [];
            const ICONO = n => {
                n = n.toLowerCase();
                if (n.includes('laptop')) return '💻';
                if (n.includes('pc') || n.includes('escritorio')) return '🖥️';
                if (n.includes('celular') || n.includes('smartphone')) return '📱';
                if (n.includes('impresora')) return '🖨️';
                if (n.includes('tablet')) return '📲';
                if (n.includes('monitor')) return '🖥️';
                return '🔧';
            };
            if (tipos.length === 0) {
                await bot(
                    'Reparamos laptops, PCs de escritorio, celulares, tablets, impresoras y más. 🔧<br>¿Tienes dudas sobre tu equipo? Escríbenos.'
                    );
            } else {
                const lista = tipos.map(t => `${ICONO(t.nombre)} ${t.nombre}`).join('<br>');
                await bot(`🔧 <strong>Equipos que reparamos:</strong><br><br>${lista}`);
            }
            await wait(700);
            mostrarOpts([{
                    ico: '💰',
                    txt: 'Ver precios de servicios',
                    fn: () => {
                        userSay('Ver precios');
                        flujoServicios()
                    }
                },
                {
                    ico: '💬',
                    txt: 'Consultar por mi equipo',
                    cls: 'wsp',
                    fn: () => flujoWsp()
                },
                {
                    ico: '🏠',
                    txt: 'Menú principal',
                    cls: 'mini',
                    fn: () => menu()
                },
            ]);
        }

        async function flujoServicios() {
            const bd = await cargarBD();
            const servicios = bd.servicios ?? [];
            if (servicios.length === 0) {
                await bot(`💰 <strong>Precios orientativos:</strong><br><br>` + tablaHtml([{
                        n: 'Diagnóstico',
                        p: 'GRATIS'
                    },
                    {
                        n: 'Mantenimiento / limpieza',
                        p: 'Desde S/.30'
                    },
                    {
                        n: 'Formateo + Windows',
                        p: 'Desde S/.40'
                    },
                    {
                        n: 'Cambio de pantalla',
                        p: 'Desde S/.60'
                    },
                    {
                        n: 'Cambio de batería',
                        p: 'Desde S/.40'
                    },
                    {
                        n: 'Cambio a SSD',
                        p: 'Desde S/.40'
                    },
                    {
                        n: 'Reparación de placa',
                        p: 'Desde S/.80'
                    },
                ]));
            } else {
                await bot(`💰 <strong>Servicios y precios:</strong><br><br>` + tablaHtml(servicios.map(s =>
                    ({
                        n: s.nombre,
                        p: s.precio
                    }))));
            }
            await wait(500);
            await bot(
                `ℹ️ Precios <strong>orientativos</strong>. El precio exacto se confirma tras el <strong>diagnóstico gratuito</strong>. Garantía: <strong>30 días</strong>.`
                );
            await wait(700);
            mostrarOpts([{
                    ico: '💬',
                    txt: 'Consultar precio exacto',
                    cls: 'wsp',
                    fn: () => flujoWsp('Hola, quiero consultar el precio exacto de un servicio')
                },
                {
                    ico: '🏠',
                    txt: 'Menú principal',
                    cls: 'mini',
                    fn: () => menu()
                },
            ]);
        }

        function tablaHtml(rows) {
            const filas = rows.map(r =>
                `<tr style="border-bottom:1px solid rgba(255,255,255,.04);">` +
                `<td style="padding:5px;font-size:.79rem;color:rgba(255,255,255,.85);">${esc(r.n)}</td>` +
                `<td style="padding:5px 4px;font-size:.76rem;color:#ffb060;font-weight:700;white-space:nowrap;">${esc(r.p)}</td>` +
                `</tr>`
            ).join('');
            return `<table style="width:100%;border-collapse:collapse;">` +
                `<tr style="border-bottom:1px solid rgba(240,120,32,.3);">` +
                `<th style="text-align:left;padding:4px 5px;font-size:.73rem;color:rgba(255,255,255,.4);">Servicio</th>` +
                `<th style="text-align:left;padding:4px;font-size:.73rem;color:rgba(255,255,255,.4);">Precio</th>` +
                `</tr>${filas}</table>`;
        }

        async function flujoComofunciona() {
            function step(num, icon, text) {
                return `<div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:6px;">` +
                    `<span style="background:#25D366;color:white;border-radius:50%;min-width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;font-size:0.7em;font-weight:700;margin-top:2px;">${num}</span>` +
                    `<span style="color:#f0f0f0;font-size:0.95em;">${icon} ${text}</span>` +
                    `</div>`;
            }
            await bot(
                `<div style="font-family:inherit;line-height:1.65;padding:4px 0;">` +
                `<p style="margin:0 0 12px;font-weight:700;font-size:1em;color:#f0f0f0;">📋 ¿Cómo funciona el servicio?</p>` +
                step("1", "📍", `Traes tu equipo a <strong>Jr. Ayaviri 741</strong>`) +
                step("2", "🔍", `<strong>Diagnóstico GRATIS</strong> — sin compromiso`) +
                step("3", "💰", `Te damos el presupuesto`) +
                step("4", "👍", `Lo aprobás → dejás tu equipo y empezamos`) +
                `<div style="margin:10px 0;padding:10px 13px;background:#2a2200;border-left:3px solid #f5a623;border-radius:6px;">` +
                `<p style="margin:0 0 4px;font-size:0.85em;font-weight:700;color:#f5a623;">⚠️ Solo si surge algo inesperado:</p>` +
                `<p style="margin:0;font-size:0.88em;color:#e0d5c0;">Si al abrir el equipo encontramos una pieza adicional o daño oculto, <strong style="color:#fff;">te llamamos antes de continuar</strong>. Tú decidís si seguimos. <em>El precio solo cambia con tu aprobación.</em></p>` +
                `</div>` +
                step("5", "🔧", `Terminamos la reparación`) +
                step("6", "📲", `Te avisamos por <strong>WhatsApp</strong> que está listo`) +
                step("7", "🛡️",
                    `Venís, pagás y te llevás tu equipo con <strong>garantía de 30 días</strong>`) +
                `<p style="margin:12px 0 0;font-size:0.85em;color:#aaa;">¿Querés traer tu equipo o tenés alguna consulta? 😊</p>` +
                `</div>`
            );
            await wait(500);
            await bot(
                `⏰ Lun–Vie 9am–7pm · Sáb 9am–2pm · Dom cerrado<br>📍 Jr. Ayaviri 741, Plaza San José, Juliaca`
                );
            await wait(700);
            mostrarOpts([{
                    ico: '🔍',
                    txt: 'Consultar mi equipo',
                    cls: 'naranja',
                    fn: () => {
                        userSay('Consultar mi equipo');
                        flujoConsultar()
                    }
                },
                {
                    ico: '💬',
                    txt: 'Hablar con asesor',
                    cls: 'wsp',
                    fn: () => flujoWsp()
                },
                {
                    ico: '🏠',
                    txt: 'Menú principal',
                    cls: 'mini',
                    fn: () => menu()
                },
            ]);
        }

        async function flujoOtras() {
            await bot('¿Qué quieres saber?');
            await wait(400);
            mostrarOpts([{
                    ico: '📍',
                    txt: 'Ubicación',
                    fn: () => {
                        userSay('¿Dónde están?');
                        flujoUbicacion()
                    }
                },
                {
                    ico: '⏰',
                    txt: 'Horarios',
                    fn: () => {
                        userSay('Horario');
                        flujoHorario()
                    }
                },
                {
                    ico: '🛡️',
                    txt: 'Garantía',
                    fn: () => {
                        userSay('Garantía');
                        flujoGarantia()
                    }
                },
                {
                    ico: '🔎',
                    txt: 'Marcas que reparan',
                    fn: () => {
                        userSay('¿Qué marcas?');
                        flujoMarcas()
                    }
                },
                {
                    ico: '🤖',
                    txt: '¿Quién eres?',
                    fn: () => {
                        userSay('¿Quién eres?');
                        flujoQuienSoy()
                    }
                },
                {
                    ico: '💬',
                    txt: 'Hablar con alguien',
                    cls: 'wsp',
                    fn: () => flujoWsp()
                },
                {
                    ico: '🏠',
                    txt: 'Menú principal',
                    cls: 'mini',
                    fn: () => menu()
                },
            ]);
        }

        async function flujoUbicacion() {
            await bot(
                `📍 <strong>Jr. Ayaviri Nro. 741, Plaza San José, Juliaca</strong><br><br>Estamos en el centro de Juliaca. ¡Fácil de encontrar! 😊`
                );
            await wait(700);
            btnMenu();
        }
        async function flujoHorario() {
            await bot(
                `⏰ <strong>Horario de atención:</strong><br><br>📅 Lun–Vie: <strong>9:00am – 7:00pm</strong><br>📅 Sábado: <strong>9:00am – 2:00pm</strong><br>❌ Domingo: Cerrado<br><br>Puedes venir sin cita previa. 😊`
                );
            await wait(700);
            btnMenu();
        }
        async function flujoGarantia() {
            await bot(
                `🛡️ <strong>Garantía de 30 días</strong> incluida en todos los trabajos.<br><br>✅ Cubre el mismo problema reparado<br>✅ Sin costo adicional<br>❌ No cubre golpes, caídas o agua<br><br>Si el problema regresa antes de 30 días → revisión <strong>gratis</strong>. 💪`
                );
            await wait(700);
            btnMenu();
        }
        async function flujoMarcas() {
            await bot(
                `🔧 <strong>Marcas que atendemos:</strong><br><br>💻 HP · Dell · Lenovo · ASUS · Acer · Toshiba · MSI<br>📱 Samsung · Huawei · Xiaomi · iPhone · LG · Motorola<br>🍎 Mac (Apple) · tablets · y muchas más`
                );
            await wait(700);
            mostrarOpts([{
                    ico: '💬',
                    txt: 'Consultar mi marca',
                    cls: 'wsp',
                    fn: () => flujoWsp()
                },
                {
                    ico: '🏠',
                    txt: 'Menú principal',
                    cls: 'mini',
                    fn: () => menu()
                },
            ]);
        }
        async function flujoWsp(msg = '') {
            const txt = encodeURIComponent(msg ||
                'Hola, necesito información sobre el servicio técnico de Nexos Tiendas');
            await bot(
                `<a href="https://wa.me/51986560904?text=${txt}" target="_blank"
                 style="display:inline-flex;align-items:center;gap:8px;background:#25d366;color:#fff;
                        border-radius:10px;padding:11px 18px;font-size:.88rem;font-weight:700;text-decoration:none;">
                 <i class="bi bi-whatsapp"></i> Escribir por WhatsApp
                 </a><br><br>📞 O llama al <strong>+51 986 560 904</strong>`
            );
            await wait(700);
            btnMenu();
        }

        /* ── Ocultar badge y robot a los 8s si no se abrió el chat ── */
        setTimeout(() => {
            if (!badgeOculto && !abierto) {
                $badge().classList.add('hidden');
                $robot().classList.add('hidden');
                badgeOculto = true;
            }
        }, 8000);

        return {
            abrir,
            cerrar,
            toggle,
            enviar
        };
    })();
</script>
