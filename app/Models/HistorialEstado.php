<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialEstado extends Model
{
    protected $table = 'historial_estados';

    protected $fillable = [
        'orden_id',
        'estado_anterior',
        'estado_nuevo',
        'observacion',
        'usuario_id'
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
