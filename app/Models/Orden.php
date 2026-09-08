<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected $table = 'ordenes';

    protected $fillable = [
        'codigo',
        'cliente_id',
        'tipo_equipo_id',
        'marca',
        'modelo',
        'descripcion',
        'foto',
        'tecnico_id',
        'estado',
        'costo_estimado',
        'total_final',
        'estado_pago',
        'tipo_atencion',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function tipoEquipo()
    {
        return $this->belongsTo(TipoEquipo::class, 'tipo_equipo_id');
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    public function servicios()
    {
        return $this->hasMany(OrdenServicio::class);
    }

    public function adicionales()
    {
        return $this->hasMany(Adicional::class);
    }

    public function historial()
    {
        return $this->hasMany(HistorialEstado::class);
    }

    public function garantia()
    {
        return $this->hasOne(\App\Models\Garantia::class);
    }

    public function badgeEstado()
    {
        return match ($this->estado) {
            'recibido'             => '<span class="badge bg-secondary">Recibido</span>',
            'en_revision'          => '<span class="badge bg-info">En Revisión</span>',
            'esperando_aprobacion' => '<span class="badge bg-warning">Esp. Aprobación</span>',
            'en_reparacion'        => '<span class="badge bg-primary">En Reparación</span>',
            'listo'                => '<span class="badge bg-success">Listo</span>',
            'entregado'            => '<span class="badge bg-dark">Entregado</span>',
            default                => '<span class="badge bg-secondary">—</span>',
        };
    }

    // Generar código automático NX-AÑO-XXXX sin duplicados
    public static function generarCodigo()
    {
        $anio    = date('Y');
        $prefijo = 'NX-' . $anio . '-';

        // Buscar el número más alto registrado este año
        $ultimo = self::where('codigo', 'like', $prefijo . '%')
            ->orderByRaw('CAST(SUBSTRING(codigo, ?) AS UNSIGNED) DESC', [strlen($prefijo) + 1])
            ->value('codigo');

        $numero = $ultimo ? ((int) substr($ultimo, strlen($prefijo))) + 1 : 1;

        // Bucle de seguridad por si hay colisión
        $codigo = $prefijo . str_pad($numero, 4, '0', STR_PAD_LEFT);
        while (self::where('codigo', $codigo)->exists()) {
            $numero++;
            $codigo = $prefijo . str_pad($numero, 4, '0', STR_PAD_LEFT);
        }

        return $codigo;
    }

    public function calcularTotal()
    {
        $servicios   = $this->servicios->sum('precio');
        $adicionales = $this->adicionales->where('estado', 'aprobado')->sum('costo');
        return $servicios + $adicionales;
    }
    // Agregar método badge al final de la clase:
    public function badgeTipoAtencion(): string
    {
        return match ($this->tipo_atencion) {
            'espera' => '<span class="badge bg-warning text-dark">
                        <i class="bi bi-hourglass-split"></i> Cliente espera
                    </span>',
            'deja'   => '<span class="badge bg-primary">
                        <i class="bi bi-box-arrow-in-down"></i> Dejó el equipo
                    </span>',
            default  => '<span class="badge bg-secondary">—</span>',
        };
    }
}
