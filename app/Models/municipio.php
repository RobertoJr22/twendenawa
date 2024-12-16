<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class municipio extends Model
{
    protected $table = 'municipios';

    protected $fillable =[
        'nome',
        'estado',
    ];

    public function distritos(){
        return $this->hasMany(distrito::class,'municipios_id');
    }
}
