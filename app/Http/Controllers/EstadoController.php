<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Garantia;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EstadoController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q', '');

        $ordenes = Orden::with(['cliente', 'tipoEquipo', 'tecnico'])
            ->whereNotIn('estado', ['entregado'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('codigo', 'like', "%{$q}%")
                        ->orWhereHas(
                            'cliente',
                            fn($c) =>
                            $c->where('nombre', 'like', "%{$q}%")
                                ->orWhere('celular', 'like', "%{$q}%")
                        );
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->only('q'));

        if ($request->ajax()) {
            return response()->json([
                'filas'      => view('admin.estados._tabla_filas', compact('ordenes'))->render(),
                'paginacion' => view('admin.estados._paginacion',  compact('ordenes'))->render(),
                'total'      => $ordenes->total(),
            ]);
        }

        return view('admin.estados.index', compact('ordenes', 'q'));
    }

    public function cambiar(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:recibido,en_revision,esperando_aprobacion,en_reparacion,listo,entregado',
        ]);

        $orden = Orden::with(['cliente', 'servicios.servicio', 'adicionales'])->findOrFail($id);
        $estadoAnterior = $orden->estado;
        $nuevoEstado    = $request->estado;

        $orden->estado = $nuevoEstado;

        // ── Si se entrega: calcular total y crear garantía ───
        if ($nuevoEstado === 'entregado') {
            $orden->total_final = $orden->calcularTotal();
            $orden->estado_pago = 'pagado';

            $diasGarantia = (int) ($request->dias_garantia ?? 30);
            if ($diasGarantia > 0) {
                Garantia::updateOrCreate(
                    ['orden_id' => $orden->id],
                    [
                        'cliente_id'    => $orden->cliente_id,
                        'dias_garantia' => $diasGarantia,
                        'fecha_inicio'  => now(),
                        'fecha_fin'     => now()->addDays($diasGarantia),
                        'estado'        => 'vigente',
                    ]
                );
            }
        }

        $orden->save();

        // ── Enviar WhatsApp si el estado cambió a "listo" ───
        if ($nuevoEstado === 'listo' && $estadoAnterior !== 'listo') {
            $this->notificarListo($orden);
        }

        // ── CORREGIDO: "listo" solo notifica, no abre ticket ─
        if ($nuevoEstado === 'listo') {
            return redirect()->route('admin.estados.index')
                ->with('mensaje', 'Estado actualizado — se notificó al cliente por WhatsApp')
                ->with('icono', 'success');
        }

        // ── Ticket2 SOLO al marcar entregado ─────────────────
        if ($nuevoEstado === 'entregado') {
            return redirect()->route('admin.estados.index')
                ->with('mensaje', 'Orden entregada correctamente — Recibo generado')
                ->with('icono', 'success')
                ->with('abrir_ticket', route('admin.ticket2', $orden->id));
        }

        return redirect()->route('admin.estados.index')
            ->with('mensaje', 'Estado actualizado correctamente')
            ->with('icono', 'success');
    }

    private function notificarListo(Orden $orden): void
    {
        $celular = $orden->cliente->celular ?? null;
        if (!$celular) return;

        $nombre = $orden->cliente->nombre;
        $codigo = $orden->codigo;
        $equipo = $orden->tipoEquipo->nombre;
        $marca  = $orden->marca ? " {$orden->marca}" : '';
        $total  = number_format($orden->calcularTotal(), 2);
        $url    = config('app.url') . '/consulta';

        $mensaje = "*NEXOS TIENDAS - Soporte Técnico*\n\n"
            . "Hola *{$nombre}*, tu equipo ya está listo para recoger 🎉\n\n"
            . "*Orden:* {$codigo}\n"
            . "*Equipo:* {$equipo}{$marca}\n"
            . "*Total a pagar:* S/. {$total}\n\n"
            . "🏬 *Recojo en tienda:* Juliaca, Puno\n"
            . "🕐 Lun–Vie 9am–7pm | Sáb 9am–1pm\n\n"
            . "Puedes ver el estado de tu equipo en:\n"
            . "🔗 {$url}\n"
            . "Código: *{$codigo}*\n\n"
            . "_Gracias por confiar en NEXOS TIENDAS_ 🙌";

        $whatsapp = new WhatsAppService();
        $enviado  = $whatsapp->enviar($celular, $mensaje);

        if (!$enviado) {
            Log::warning("No se pudo enviar WhatsApp a orden {$codigo}");
        }
    }
}
