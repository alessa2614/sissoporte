<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Garantia extends Model
{
    protected $table = 'garantias';

    protected $fillable = [
        'orden_id',
        'fecha_inicio',
        'fecha_fin',
        'dias',
        'estado',
        'observacion'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class);
    }

    // Verifica si está vigente comparando con hoy
    public function estaVigente()
    {
        return $this->fecha_fin >= now()->toDateString()
            && $this->estado !== 'usada';
    }

    // Días restantes
    public function diasRestantes()
    {
        return max(0, now()->diffInDays($this->fecha_fin, false));
    }
}
