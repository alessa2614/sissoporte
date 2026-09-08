<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Cliente;
use App\Models\TipoEquipo;
use App\Models\User;
use App\Models\CatalogoServicio;
use App\Models\HistorialEstado;
use App\Models\Garantia;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdenController extends Controller
{
    public function index(Request $request)
    {
        $q       = $request->input('q', '');
        $periodo = $request->input('periodo', 'todos');
        $pago    = $request->input('pago', 'todos');

        $ordenes = Orden::with(['cliente', 'tipoEquipo', 'tecnico'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('codigo', 'like', "%{$q}%")
                        ->orWhereHas('cliente', fn($c) => $c->where('nombre', 'like', "%{$q}%"));
                });
            })
            ->when($periodo === 'hoy',    fn($q) => $q->whereDate('created_at', today()))
            ->when($periodo === 'semana', fn($q) => $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]))
            ->when($pago !== 'todos',     fn($q) => $q->where('estado_pago', $pago))
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->only(['q', 'periodo', 'pago']));

        if ($request->ajax()) {
            return response()->json([
                'filas'      => view('admin.ordenes._tabla_filas', compact('ordenes'))->render(),
                'paginacion' => view('admin.ordenes._paginacion',  compact('ordenes'))->render(),
                'total'      => $ordenes->total(),
            ]);
        }

        return view('admin.ordenes.index', compact('ordenes', 'q', 'periodo', 'pago'));
    }

    public function create(Request $request)
    {
        $clienteSeleccionado = $request->query('cliente_id');
        $clientes  = Cliente::orderBy('nombre')->get();
        $tipos     = TipoEquipo::orderBy('nombre')->get();
        $tecnicos  = User::where('estado', true)
            ->whereHas('roles', fn($q) => $q->where('name', 'TECNICO'))
            ->get();
        $servicios = CatalogoServicio::orderBy('nombre')->get();

        $clientesJs = $clientes->map(fn($c) => [
            'id'      => $c->id,
            'nombre'  => $c->nombre,
            'celular' => $c->celular,
            'correo'  => $c->correo,
        ])->values();

        return view('admin.ordenes.create', compact(
            'clientes',
            'tipos',
            'tecnicos',
            'servicios',
            'clienteSeleccionado',
            'clientesJs'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_atencion'           => 'required|in:espera,deja',
            'cliente_id'              => 'required|exists:clientes,id',
            'tipo_equipo_id'          => 'required|exists:tipo_equipos,id',
            'marca'                   => 'nullable|string|max:80',
            'modelo'                  => 'nullable|string|max:80',
            'descripcion'             => 'nullable|string',
            'tecnico_id'              => 'nullable|exists:users,id',
            'costo_estimado'          => 'nullable|numeric|min:0',
            'foto'                    => 'nullable|image|max:2048',
            'servicios'               => 'nullable|array',
            'servicios.*.servicio_id' => 'required|exists:catalogo_servicios,id',
            'servicios.*.precio'      => 'required|numeric|min:0',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('ordenes', 'public');
        }

        $orden                 = new Orden();
        $orden->codigo         = Orden::generarCodigo();
        $orden->cliente_id     = $request->cliente_id;
        $orden->tipo_equipo_id = $request->tipo_equipo_id;
        $orden->marca          = $request->marca;
        $orden->modelo         = $request->modelo;
        $orden->descripcion    = $request->descripcion ?? '—';
        $orden->tecnico_id     = $request->tecnico_id;
        $orden->costo_estimado = $request->costo_estimado;
        $orden->foto           = $fotoPath;
        $orden->estado         = 'recibido';
        $orden->estado_pago    = 'pendiente';
        $orden->tipo_atencion  = $request->tipo_atencion;
        $orden->save();

        if ($request->has('servicios')) {
            foreach ($request->servicios as $s) {
                \App\Models\OrdenServicio::create([
                    'orden_id'    => $orden->id,
                    'servicio_id' => $s['servicio_id'],
                    'precio'      => $s['precio'],
                    'observacion' => $s['observacion'] ?? null,
                ]);
            }
        }

        HistorialEstado::create([
            'orden_id'        => $orden->id,
            'estado_anterior' => null,
            'estado_nuevo'    => 'recibido',
            'observacion'     => 'Orden registrada',
            'usuario_id'      => Auth::id(),
        ]);

        // ── WhatsApp solo si el cliente DEJA el equipo ───────────
        if ($orden->tipo_atencion === 'deja') {
            $this->notificarNuevaOrden($orden);

            return redirect()->route('admin.ordenes.show', $orden->id)
                ->with('mensaje', 'Orden ' . $orden->codigo . ' registrada — Ticket de ingreso generado')
                ->with('icono', 'success')
                ->with('abrir_ticket', route('admin.ticket1', $orden->id));
        }

        // ── Cliente ESPERA: sin ticket1, sin WhatsApp ────────────
        return redirect()->route('admin.ordenes.show', $orden->id)
            ->with('mensaje', 'Orden ' . $orden->codigo . ' registrada — Cliente en espera')
            ->with('icono', 'info');
    }

    // ── Entrega directa para cliente en espera ────────────────────

    // ── WhatsApp: nueva orden (solo para cliente que deja) ────────
    private function notificarNuevaOrden(Orden $orden): void
    {
        $celular = $orden->cliente->celular ?? null;
        if (!$celular) return;

        $orden->load(['cliente', 'tipoEquipo', 'servicios.servicio']);

        $nombre = $orden->cliente->nombre;
        $codigo = $orden->codigo;
        $equipo = $orden->tipoEquipo->nombre;
        $marca  = $orden->marca  ? " {$orden->marca}"  : '';
        $modelo = $orden->modelo ? " {$orden->modelo}" : '';
        $url    = 'nexos-soporte.test/consulta';

        $listaServicios = '';
        if ($orden->servicios->count() > 0) {
            foreach ($orden->servicios as $s) {
                $listaServicios .= "   • {$s->servicio->nombre} — S/. " . number_format($s->precio, 2) . "\n";
            }
            $estimado = "💰 *Estimado:* S/. " . number_format($orden->servicios->sum('precio'), 2) . "\n";
        } else {
            $estimado = $orden->costo_estimado
                ? "💰 *Estimado:* S/. " . number_format($orden->costo_estimado, 2) . "\n"
                : '';
        }

        $mensaje = "*NEXOS TIENDAS - Soporte Técnico*\n\n"
            . "Hola *{$nombre}*, tu equipo ha sido registrado \n\n"
            . "*Código de orden:* `{$codigo}`\n"
            . "*Equipo:* {$equipo}{$marca}{$modelo}\n"
            . "*Problema:* {$orden->descripcion}\n\n"
            . ($listaServicios ? "👨‍💻 *Servicios a realizar:*\n{$listaServicios}\n" : '')
            . $estimado
            . "\n🏬 *Tienda:* Juliaca, Puno\n"
            . "🕐 Lun–Vie 9am–7pm | Sáb 9am–1pm\n\n"
            . "Sigue el estado de tu equipo:\n"
            . "🔗 {$url}\n"
            . "Código: *{$codigo}*\n\n"
            . "_Te avisaremos cuando esté listo_ 🙌";

        (new WhatsAppService())->enviar($celular, $mensaje);
    }

    public function show($id)
    {
        $orden = Orden::with([
            'cliente',
            'tipoEquipo',
            'tecnico',
            'servicios.servicio',
            'adicionales',
            'historial.usuario'
        ])->findOrFail($id);

        return view('admin.ordenes.show', compact('orden'));
    }

    public function edit($id)
    {
        $orden    = Orden::with(['servicios.servicio'])->findOrFail($id);
        $clientes = Cliente::orderBy('nombre')->get();
        $tipos    = TipoEquipo::orderBy('nombre')->get();
        $tecnicos = User::where('estado', true)
            ->whereHas('roles', fn($q) => $q->where('name', 'TECNICO'))
            ->get();
        $servicios = CatalogoServicio::orderBy('nombre')->get();

        $clientesJs = $clientes->map(fn($c) => [
            'id'      => $c->id,
            'nombre'  => $c->nombre,
            'celular' => $c->celular,
            'correo'  => $c->correo,
        ])->values();

        $serviciosOrden = $orden->servicios->map(fn($s) => [
            'servicio_id' => $s->servicio_id,
            'nombre'      => optional($s->servicio)->nombre ?? '(sin nombre)',
            'precio'      => $s->precio,
            'observacion' => $s->observacion ?? '',
        ])->values();

        return view('admin.ordenes.edit', compact(
            'orden',
            'clientes',
            'tipos',
            'tecnicos',
            'servicios',
            'clientesJs',
            'serviciosOrden'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tipo_equipo_id' => 'required|exists:tipo_equipos,id',
            'marca'          => 'nullable|string|max:80',
            'modelo'         => 'nullable|string|max:80',
            'descripcion'    => 'nullable|string',
            'tecnico_id'     => 'nullable|exists:users,id',
            'foto'           => 'nullable|image|max:2048',
        ]);

        $orden = Orden::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($orden->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($orden->foto);
            }
            $orden->foto = $request->file('foto')->store('ordenes', 'public');
        } elseif ($request->boolean('eliminar_foto') && $orden->foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($orden->foto);
            $orden->foto = null;
        }

        $orden->tipo_equipo_id = $request->tipo_equipo_id;
        $orden->marca          = $request->marca;
        $orden->modelo         = $request->modelo;
        $orden->descripcion    = $request->descripcion ?? '—';
        $orden->tecnico_id     = $request->tecnico_id;
        $orden->save();

        return redirect()->route('admin.ordenes.show', $orden->id)
            ->with('mensaje', 'Orden actualizada correctamente')
            ->with('icono', 'success');
    }

    public function destroy($id)
    {
        $orden = Orden::findOrFail($id);
        if ($orden->servicios()->exists()) {
            return redirect()->route('admin.ordenes.index')
                ->with('mensaje', 'No se puede eliminar la orden porque tiene servicios asociados.')
                ->with('icono', 'error');
        }
        $orden->delete();

        return redirect()->route('admin.ordenes.index')
            ->with('mensaje', 'Orden eliminada exitosamente')
            ->with('icono', 'success');
    }

    public function agregarServicio(Request $request, $id)
    {
        $request->validate([
            'servicio_id' => 'required|exists:catalogo_servicios,id',
            'precio'      => 'required|numeric|min:0',
            'observacion' => 'nullable|string|max:255',
        ]);

        \App\Models\OrdenServicio::create([
            'orden_id'    => $id,
            'servicio_id' => $request->servicio_id,
            'precio'      => $request->precio,
            'observacion' => $request->observacion,
        ]);

        return redirect()->route('admin.ordenes.show', $id)
            ->with('mensaje', 'Servicio agregado exitosamente')
            ->with('icono', 'success');
    }

    public function eliminarServicio($id)
    {
        $servicio = \App\Models\OrdenServicio::findOrFail($id);
        $ordenId  = $servicio->orden_id;
        $servicio->delete();

        return redirect()->route('admin.ordenes.show', $ordenId)
            ->with('mensaje', 'Servicio eliminado')
            ->with('icono', 'success');
    }

    public function agregarAdicional(Request $request, $id)
    {
        $request->validate([
            'descripcion'   => 'required|string',
            'costo'         => 'required|numeric|min:0',
            'fecha_llamada' => 'nullable|date',
            'observacion'   => 'nullable|string',
        ]);

        \App\Models\Adicional::create([
            'orden_id'      => $id,
            'descripcion'   => $request->descripcion,
            'costo'         => $request->costo,
            'estado'        => 'pendiente',
            'fecha_llamada' => $request->fecha_llamada,
            'observacion'   => $request->observacion,
        ]);

        $orden = Orden::findOrFail($id);
        if (!in_array($orden->estado, ['esperando_aprobacion', 'entregado'])) {
            $estadoAnterior = $orden->estado;
            $orden->estado  = 'esperando_aprobacion';
            $orden->save();

            HistorialEstado::create([
                'orden_id'        => $id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo'    => 'esperando_aprobacion',
                'observacion'     => 'Adicional pendiente de aprobación del cliente',
                'usuario_id'      => Auth::id(),
            ]);
        }

        return redirect()->route('admin.ordenes.show', $id)
            ->with('mensaje', 'Adicional registrado — estado cambiado a "Esperando Aprobación"')
            ->with('icono', 'info');
    }

    public function aprobarAdicional($id)
    {
        $adicional         = \App\Models\Adicional::findOrFail($id);
        $adicional->estado = 'aprobado';
        $adicional->save();

        $orden      = Orden::findOrFail($adicional->orden_id);
        $pendientes = $orden->adicionales()->where('estado', 'pendiente')->count();
        if ($pendientes === 0 && $orden->estado === 'esperando_aprobacion') {
            $orden->estado = 'en_reparacion';
            $orden->save();

            HistorialEstado::create([
                'orden_id'        => $orden->id,
                'estado_anterior' => 'esperando_aprobacion',
                'estado_nuevo'    => 'en_reparacion',
                'observacion'     => 'Adicional aprobado — se retoma la reparación',
                'usuario_id'      => Auth::id(),
            ]);
        }

        return redirect()->route('admin.ordenes.show', $adicional->orden_id)
            ->with('mensaje', 'Adicional aprobado')
            ->with('icono', 'success');
    }

    public function rechazarAdicional($id)
    {
        $adicional         = \App\Models\Adicional::findOrFail($id);
        $adicional->estado = 'rechazado';
        $adicional->save();

        $orden      = Orden::findOrFail($adicional->orden_id);
        $pendientes = $orden->adicionales()->where('estado', 'pendiente')->count();
        if ($pendientes === 0 && $orden->estado === 'esperando_aprobacion') {
            $orden->estado = 'en_reparacion';
            $orden->save();

            HistorialEstado::create([
                'orden_id'        => $orden->id,
                'estado_anterior' => 'esperando_aprobacion',
                'estado_nuevo'    => 'en_reparacion',
                'observacion'     => 'Adicional rechazado — se retoma la reparación',
                'usuario_id'      => Auth::id(),
            ]);
        }

        return redirect()->route('admin.ordenes.show', $adicional->orden_id)
            ->with('mensaje', 'Adicional rechazado')
            ->with('icono', 'warning');
    }

    // ── Cambiar tipo de atención: espera ↔ deja ───────────────
    public function cambiarTipo(Request $request, $id)
    {
        $orden = Orden::findOrFail($id);

        // No permitir cambio si ya está entregada
        if ($orden->estado === 'entregado') {
            return redirect()->route('admin.ordenes.show', $id)
                ->with('mensaje', 'No se puede cambiar el tipo en una orden cerrada.')
                ->with('icono', 'error');
        }

        $tipoAnterior = $orden->tipo_atencion;
        $nuevoTipo    = $request->input('tipo_atencion');

        // Validar que sea un valor permitido
        if (!in_array($nuevoTipo, ['espera', 'deja'])) {
            return redirect()->route('admin.ordenes.show', $id)
                ->with('mensaje', 'Tipo de atención no válido.')
                ->with('icono', 'error');
        }

        $orden->tipo_atencion = $nuevoTipo;
        $orden->save();

        $observaciones = [
            'deja'  => 'Modalidad cambiada a "Dejó equipo" — el cliente no pudo esperar',
            'espera' => 'Modalidad cambiada a "Cliente en espera" — el cliente regresó',
        ];

        HistorialEstado::create([
            'orden_id'        => $orden->id,
            'estado_anterior' => $orden->estado,
            'estado_nuevo'    => $orden->estado,
            'observacion'     => $observaciones[$nuevoTipo],
            'usuario_id'      => Auth::id(),
        ]);

        // Si cambió a 'deja', notificar por WhatsApp y abrir Ticket 1
        if ($nuevoTipo === 'deja') {
            $this->notificarNuevaOrden($orden->fresh(['cliente', 'tipoEquipo', 'servicios.servicio']));

            return redirect()->route('admin.ordenes.show', $id)
                ->with('mensaje', 'Modalidad actualizada — se envió WhatsApp al cliente')
                ->with('icono', 'success')
                ->with('abrir_ticket', route('admin.ticket1', $id));
        }

        return redirect()->route('admin.ordenes.show', $id)
            ->with('mensaje', 'Modalidad actualizada a "Cliente en espera"')
            ->with('icono', 'info');
    }

    // ── Entrega directa — CORREGIDO: estado_anterior dinámico ─
    public function entregarDirecto(Request $request, $id)
    {
        $orden = Orden::with(['cliente', 'servicios', 'adicionales'])->findOrFail($id);

        // Guardar estado real antes de cambiar
        $estadoAnterior = $orden->estado;

        $orden->estado      = 'entregado';
        $orden->total_final = $orden->calcularTotal();
        $orden->estado_pago = 'pagado';
        $orden->save();

        Garantia::updateOrCreate(
            ['orden_id' => $orden->id],
            [
                'cliente_id'    => $orden->cliente_id,
                'dias_garantia' => 30,
                'fecha_inicio'  => now(),
                'fecha_fin'     => now()->addDays(30),
                'estado'        => 'vigente',
            ]
        );

        HistorialEstado::create([
            'orden_id'        => $orden->id,
            'estado_anterior' => $estadoAnterior,   // ← antes era 'recibido' hardcodeado
            'estado_nuevo'    => 'entregado',
            'observacion'     => 'Entrega directa — cliente en espera',
            'usuario_id'      => Auth::id(),
        ]);

        return redirect()->route('admin.ordenes.show', $orden->id)
            ->with('mensaje', 'Orden entregada correctamente — Recibo generado')
            ->with('icono', 'success')
            ->with('abrir_ticket', route('admin.ticket2', $orden->id));
    }
}
