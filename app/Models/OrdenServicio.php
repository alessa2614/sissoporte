<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenServicio extends Model
{
    protected $table = 'orden_servicios';

    protected $fillable = [
        'orden_id',
        'servicio_id',
        'precio',
        'observacion'
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class);
    }

    public function servicio()
    {
        return $this->belongsTo(CatalogoServicio::class, 'servicio_id');
    }
}
