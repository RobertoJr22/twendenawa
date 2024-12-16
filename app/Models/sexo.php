<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sexo extends Model
{
    protected $table ='sexos';

    protected $fillable = [
        'nome',
    ];


    public function responsavels(){
        return $this->hasMany(responsavel::class,'sexos_id');
    }

    public function motoristas(){
        return $this->hasMany(motorista::class,'sexos_id');
    }

    public function estudantes(){
        return $this->hasMany(estudante::class,'sexos_id');
    }
}
