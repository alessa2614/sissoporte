<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 5mm 4mm;
        }

        body {
            font-family: monospace;
            font-size: 9px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        /* Encabezado */
        .empresa {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 2px 0 0;
            letter-spacing: 1px;
        }

        .subtitulo {
            font-size: 7px;
            text-align: center;
            color: #444;
            margin: 1px 0 4px;
            letter-spacing: 0.5px;
        }

        .titulo {
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            margin: 3px 0 1px;
        }

        /* Código grande */
        .codigo-grande {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 3px;
            padding: 4px 0 2px;
            margin: 0;
        }

        /* Tabla de datos */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 1px 0;
            vertical-align: top;
            font-size: 9px;
        }

        .td-label {
            width: 36%;
            font-weight: bold;
        }

        /* Servicios */
        .servicio-row td {
            padding: 2px 0;
        }

        .total-row td {
            padding-top: 3px;
            font-weight: bold;
            font-size: 12px;
            border-top: 1px solid #000;
        }

        /* Advertencia y footer */
        .advertencia {
            font-size: 8px;
            font-style: italic;
            text-align: center;
            padding: 2px 0;
            line-height: 1.4;
        }

        .url-seguimiento {
            font-size: 10px;
            text-align: center;
            font-weight: bold;
            padding: 3px 0;
            letter-spacing: 1px;
        }

        .footer {
            font-size: 8px;
            text-align: center;
            color: #555;
            margin-top: 4px;
            line-height: 1.4;
        }

        p {
            margin: 2px 0;
        }
    </style>
</head>

<body>

    {{-- ENCABEZADO --}}
    <p class="center" style="margin-bottom:2px;">
        <img src="{{ public_path('assets/compiled/imagen/image.png') }}" alt="Logo" style="width:120px; height:auto;">
    </p>
    <p class="empresa">NEXOS TIENDAS</p>
    <p class="subtitulo">Soporte Técnico · Juliaca, Puno</p>

    <div class="divider"></div>

    <p class="titulo">ORDEN DE SERVICIO — TICKET DE INGRESO</p>

    <div class="divider"></div>

    {{-- CÓDIGO --}}
    <p class="codigo-grande">{{ $orden->codigo }}</p>
    <p class="center" style="font-size:8px; color:#444;">
        {{ $orden->created_at->format('d/m/Y H:i') }}
    </p>

    <div class="divider"></div>

    {{-- DATOS DEL CLIENTE Y EQUIPO --}}
    <table>
        <tr>
            <td class="td-label">Cliente:</td>
            <td>{{ $orden->cliente->nombre }}</td>
        </tr>
        <tr>
            <td class="td-label">Celular:</td>
            <td>{{ $orden->cliente->celular }}</td>
        </tr>
        <tr>
            <td class="td-label">Equipo:</td>
            <td>
                {{ $orden->tipoEquipo->nombre }}
                @if ($orden->marca)
                    — {{ $orden->marca }}
                @endif
                @if ($orden->modelo)
                    {{ $orden->modelo }}
                @endif
            </td>
        </tr>
        <tr>
            <td class="td-label">Problema:</td>
            <td>{{ $orden->descripcion }}</td>
        </tr>
        @if ($orden->tecnico)
            <tr>
                <td class="td-label">Técnico:</td>
                <td>{{ $orden->tecnico->name }}</td>
            </tr>
        @endif
    </table>

    <div class="divider"></div>

    {{-- SERVICIOS --}}
    @php
        $servicios = $orden->servicios;
        $totalServicios = $servicios->sum('precio');
    @endphp

    @if ($servicios->count() > 0)
        <p class="bold" style="margin-bottom:2px;">SERVICIOS A REALIZAR:</p>
        <table>
            @foreach ($servicios as $s)
                <tr class="servicio-row">
                    <td>• {{ $s->servicio->nombre }}</td>
                    <td class="right">S/. {{ number_format($s->precio, 2) }}</td>
                </tr>
                @if ($s->observacion)
                    <tr>
                        <td colspan="2" style="font-size:8px; color:#444; padding-left:6px;">
                            {{ $s->observacion }}
                        </td>
                    </tr>
                @endif
            @endforeach
            <tr class="total-row">
                <td>COSTO ESTIMADO:</td>
                <td class="right">S/. {{ number_format($totalServicios, 2) }}</td>
            </tr>
        </table>
    @else
        <table>
            <tr>
                <td class="bold">COSTO ESTIMADO:</td>
                <td class="right bold" style="font-size:12px;">
                    S/. {{ number_format($orden->costo_estimado ?? 0, 2) }}
                </td>
            </tr>
        </table>
    @endif

    <p class="advertencia">(sujeto a revisión — puede variar)</p>

    <div class="divider"></div>

    {{-- SEGUIMIENTO --}}
    <p class="center bold" style="font-size:9px; margin-bottom:1px;">🔍 CÓDIGO DE SEGUIMIENTO</p>
    <p class="codigo-grande">{{ $orden->codigo }}</p>
    <p class="url-seguimiento">{{ config('app.url') }}/consulta</p>
    <p class="center" style="font-size:8px; line-height:1.5;">
        Ingresa tu código en la página para ver<br>
        el estado de tu equipo en tiempo real.
    </p>

    <div class="divider"></div>

    <p class="advertencia">
        ⚠️ Si se detectan problemas adicionales,<br>
        te llamaremos antes de proceder.<br>
        Celular registrado: <strong>{{ $orden->cliente->celular }}</strong>
    </p>

    <div class="divider"></div>

    <p class="footer">
        Gracias por confiar en NEXOS TIENDAS<br>
        Impreso: {{ now()->format('d/m/Y H:i') }}
    </p>

</body>

</html>
