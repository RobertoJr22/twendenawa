<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class estudantes_responsavels extends Model
{
    protected $table = 'estudantes_responsavels';

    protected $fillable = [
        'responsavels_id',
        'estudantes_id',
        'estado'
    ];
}
