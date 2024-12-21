<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class motoristas_rotas extends Model
{
    protected $table = 'motoristas_rotas';

    protected $fillable = [
        'motoristas_id',
        'rotas_id',
        'estado'
    ];
}
