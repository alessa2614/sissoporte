<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\TipoEquipo;
use App\Models\CatalogoServicio;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;

class ChatbotController extends Controller
{
    /**
     * POST /chatbot/buscar
     */
    public function buscar(Request $request): JsonResponse
    {
        try {
            $codigo  = strtoupper(trim($request->input('codigo', '')));
            $celular = preg_replace('/\D/', '', trim($request->input('celular', '')));

            if (!$codigo || !$celular) {
                return response()->json(['encontrado' => false, 'motivo' => 'datos_incompletos']);
            }

            $cel9 = substr($celular, -9);

            // Buscar por código, verificar celular en PHP (sin SQL complejo)
            $orden = Orden::with(['cliente', 'tipoEquipo'])
                ->where('codigo', $codigo)
                ->whereHas('cliente')
                ->first();

            if ($orden) {
                $celBD = preg_replace('/\D/', '', $orden->cliente->celular ?? '');
                if (substr($celBD, -9) !== $cel9) {
                    $orden = null;
                }
            }

            if (!$orden) {
                return response()->json(['encontrado' => false, 'motivo' => 'no_encontrado']);
            }

            // URL de foto compatible con WAMP/subdirectorio
            $fotoUrl = null;
            if ($orden->foto) {
                $fotoUrl = asset('storage/' . $orden->foto);
            }

            return response()->json([
                'encontrado' => true,
                'orden' => [
                    'codigo'         => $orden->codigo,
                    'cliente_nombre' => $orden->cliente->nombre,
                    'tipo_equipo'    => $orden->tipoEquipo->nombre,
                    'marca'          => $orden->marca  ?? '',
                    'modelo'         => $orden->modelo ?? '',
                    'estado'         => $orden->estado,
                    'estado_pago'    => $orden->estado_pago ?? 'pendiente',
                    'costo_estimado' => $orden->costo_estimado
                        ? number_format($orden->costo_estimado, 2) : null,
                    'total_final'    => $orden->total_final
                        ? number_format($orden->total_final, 2) : null,
                    'foto_url'       => $fotoUrl,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'encontrado' => false,
                'motivo'     => 'excepcion',
                'error'      => $e->getMessage(),
                'linea'      => $e->getLine(),
            ]);
        }
    }

    /**
     * GET /chatbot/servicios
     * Devuelve tipos de equipo y catálogo de servicios por separado
     * (catalogo_servicios no tiene tipo_equipo_id — son servicios genéricos)
     */
    public function servicios(): JsonResponse
    {
        try {
            $tipos = TipoEquipo::orderBy('nombre')->get()
                ->map(fn($t) => ['id' => $t->id, 'nombre' => $t->nombre]);

            $servicios = CatalogoServicio::orderBy('nombre')->get()
                ->map(fn($s) => [
                    'nombre' => $s->nombre,
                    'precio' => $s->precio_base > 0
                        ? 'Desde S/. ' . number_format($s->precio_base, 0)
                        : 'Consultar',
                ]);

            return response()->json([
                'tipos'     => $tipos,
                'servicios' => $servicios,
            ]);
        } catch (\Exception $e) {
            return response()->json(['tipos' => [], 'servicios' => [], 'error' => $e->getMessage()]);
        }
    }
}
