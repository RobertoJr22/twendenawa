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
        return $this->hasMany('app\Models\responsavel');
    }

    public function motoristas(){
        return $this->hasMany('app\Models\motorista');
    }

    public function estudantes(){
        return $this->hasMany('app\Models\estudante');
    }
}
