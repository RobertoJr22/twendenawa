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
        return $this->belongsTo(estudante::class,'estudantes_id');
    }

    public function motoristas(){
        return $this->belongsTo(motorista::class,'motoristas');
    }

    public function viagems(){
        return $this->belongsTo(viagem::class,'viagems_id');
    }
}
