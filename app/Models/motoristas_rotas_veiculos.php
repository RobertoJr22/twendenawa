<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class motoristas_rotas_veiculos extends Model
{
    protected $table = 'motoristas_rotas_veiculos';

    protected $fillable = [
        'veiculos_id',
        'motoristas_id',
        'rotas_id',
        'estado'
    ];
}
