<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DadosViagem extends Model
{
    protected $table ='DadosViagems';

    protected $fillable = [
        'estudantes_id',
        'motoristas_id',
        'viagems_id',
        'estado',
    ];

    public function estudantes(){
        return $this->belongsTo('app\Models\estudante');
    }

    public function motoristas(){
        return $this->belongsTo('app\Models\motorista');
    }

    public function viagems(){
        return $this->belongsTo('app\Models\viagem');
    }
}
