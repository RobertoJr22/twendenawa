<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class turno extends Model
{
    protected $table = 'turnos';

    protected $fillable = [
        'nome',
        'HoraIda',
        'HoraRegresso',
        'estado',
    ];

    public function motoristas(){
        return $this->hasMany(motorista::class,'turnos_id');
    }

    public function estudantes(){
        return $this->hasMany(estudante::class,'turnos_id');
    }

    protected $cast = [
        'HoraIda'=>'datetime:H:i:s',
        'HoraRegresso'=>'datetime:H:i:s',
    ];
}
