<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adicional extends Model
{
    protected $table = 'adicionales';

    protected $fillable = [
        'orden_id',
        'descripcion',
        'costo',
        'estado',
        'fecha_llamada',
        'observacion'
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class);
    }
}
