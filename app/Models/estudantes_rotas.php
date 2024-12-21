<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class estudantes_rotas extends Model
{
    protected $table = 'estudantes_rotas';

    protected $fillable = [
        'rotas_id',
        'estudantes_id',
        'estado'
    ];
}
