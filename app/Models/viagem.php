<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class viagem extends Model
{
    protected $table = 'viagems';

    protected $fillable = [
        'estado',
    ];

    public function DadosViagems(){
        return $this->hasMany('app\Models\DadosViagem');
    }
}
