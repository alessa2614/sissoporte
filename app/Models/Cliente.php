<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = "clientes";
    protected $fillable = [
        'nombre',
        'celular',
        'correo',
    ];

    public function ordenes()
    {
        return $this->hasMany(\App\Models\Orden::class, 'cliente_id');
    }
}
