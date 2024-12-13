<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class carteira extends Model
{
    protected $table = 'carteiras';

    protected $fillable =[
        'NumeroCarta',
        'motoristas_id',
        'estado',
    ];

    public function motoristas(){
        return $this->belongsTo('app\Models\motorista');
    }
}
