<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // TICKET 1 — Al dejar el equipo
    public function ticket1($id)
    {
        $orden = Orden::with([
            'cliente',
            'tipoEquipo',
            'tecnico',
            'servicios.servicio', // ← para listar servicios y sumar el total estimado
        ])->findOrFail($id);

        $pdf = Pdf::loadView('tickets.ticket1', compact('orden'))
            ->setPaper([0, 0, 226.77, 500], 'portrait');

        return $pdf->stream('ticket1-' . $orden->codigo . '.pdf');
    }

    // TICKET 2 — Al recoger el equipo
    public function ticket2($id)
    {
        $orden = Orden::with([
            'cliente',
            'tipoEquipo',
            'tecnico',
            'servicios.servicio',
            'adicionales',
            'garantia',            // ← para mostrar días de garantía en el recibo
        ])->findOrFail($id);

        $pdf = Pdf::loadView('tickets.ticket2', compact('orden'))
            ->setPaper([0, 0, 226.77, 650], 'portrait');

        return $pdf->stream('ticket2-' . $orden->codigo . '.pdf');
    }
}
