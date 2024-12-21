<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rotas_veiculos extends Model
{
    protected $table = 'rotas_veiculos';

    protected $fillable = [
        'rotas_id',
        'veiculos_id',
        'estado'
    ];
}
