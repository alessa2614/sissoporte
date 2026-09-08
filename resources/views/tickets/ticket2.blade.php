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
        }

        p {
            margin: 2px 0;
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
            margin: 6px 0;
        }

        .empresa {
            font-size: 15px;
            font-weight: bold;
            text-align: center;
        }

        .titulo {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
            vertical-align: top;
        }

        .td-label {
            width: 40%;
            font-weight: bold;
        }

        .td-precio {
            text-align: right;
            width: 28%;
        }

        .total-box {
            border: 2px solid #000;
            padding: 6px;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 6px 0;
        }

        .footer {
            font-size: 9px;
            text-align: center;
            color: #555;
            margin-top: 6px;
        }

        .sec-titulo {
            font-weight: bold;
            margin: 5px 0 2px 0;
        }
    </style>
</head>

<body>

    {{-- ENCABEZADO --}}
    <p class="center">
        <img src="{{ public_path('assets/compiled/imagen/image.png') }}" alt="Logo" style="width:120px; height:auto;">
    </p>
    <p class="empresa">NEXOS TIENDAS</p>
    <p class="titulo">RECIBO FINAL DE SERVICIO</p>
    <p class="center" style="font-size:9px;">Soporte Técnico — Juliaca, Puno</p>

    <div class="divider"></div>

    {{-- CÓDIGO Y FECHA --}}
    <table>
        <tr>
            <td class="td-label">Orden:</td>
            <td class="bold">{{ $orden->codigo }}</td>
        </tr>
        <tr>
            <td class="td-label">Fecha entrega:</td>
            <td>{{ now()->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- DATOS --}}
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
        <tr>
            <td class="td-label">Técnico:</td>
            <td>{{ $orden->tecnico->name ?? '—' }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- SERVICIOS REALIZADOS --}}
    @if ($orden->servicios->count() > 0)
        <p class="sec-titulo">SERVICIOS REALIZADOS:</p>
        <table>
            @foreach ($orden->servicios as $s)
                <tr>
                    <td>• {{ $s->servicio->nombre }}</td>
                    <td class="td-precio">S/. {{ number_format($s->precio, 2) }}</td>
                </tr>
                @if ($s->observacion)
                    <tr>
                        <td colspan="2" style="font-size:9px; color:#555; padding-left:10px;">
                            {{ $s->observacion }}
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
        <div class="divider"></div>
    @endif

    {{-- ADICIONALES APROBADOS --}}
    @php $adicionales = $orden->adicionales->where('estado', 'aprobado'); @endphp
    @if ($adicionales->count() > 0)
        <p class="sec-titulo">REPUESTOS / ADICIONALES (aprobados):</p>
        <table>
            @foreach ($adicionales as $a)
                <tr>
                    <td>• {{ $a->descripcion }}</td>
                    <td class="td-precio">S/. {{ number_format($a->costo, 2) }}</td>
                </tr>
            @endforeach
        </table>
        <div class="divider"></div>
    @endif

    {{-- DIFERENCIA ESTIMADO VS TOTAL --}}
    @if ($orden->costo_estimado && $orden->costo_estimado != $orden->calcularTotal())
        <table style="font-size:10px; color:#555;">
            <tr>
                <td>Costo estimado inicial:</td>
                <td class="right">S/. {{ number_format($orden->costo_estimado, 2) }}</td>
            </tr>
            <tr>
                <td>Diferencia:</td>
                <td class="right">S/. {{ number_format($orden->calcularTotal() - $orden->costo_estimado, 2) }}</td>
            </tr>
        </table>
        <div class="divider"></div>
    @endif

    {{-- TOTAL --}}
    <div class="total-box">
        TOTAL A PAGAR EN CAJA:<br>
        S/. {{ number_format($orden->calcularTotal(), 2) }}
    </div>

    {{-- GARANTÍA --}}
    <p class="center" style="font-size:11px;">
        🛡️ Garantía: <strong>30 días</strong> por el servicio realizado<br>
        <small>Válida hasta: {{ now()->addDays(30)->format('d/m/Y') }}</small>
    </p>

    <div class="divider"></div>

    <p class="center bold" style="font-size:11px;">
        * Pase a caja para su boleta oficial *
    </p>

    <div class="divider"></div>

    <p class="footer">
        Gracias por confiar en NEXOS TIENDAS<br>
        Impreso: {{ now()->format('d/m/Y H:i') }}
    </p>

</body>

</html>
