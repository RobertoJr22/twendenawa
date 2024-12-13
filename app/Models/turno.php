<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class turno extends Model
{
    protected $table = 'turnos';

    protected $fillable = [
        'nome',
    ];

    public function motoristas(){
        return $this->hasMany('app\Models\motorista');
    }

    public function estudantes(){
        return $this->hasMany('app\Models\estudante');
    }
}
