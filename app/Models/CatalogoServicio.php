<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogoServicio extends Model
{
    protected $table = 'catalogo_servicios';

    protected $fillable = ['nombre', 'precio_base'];
}
