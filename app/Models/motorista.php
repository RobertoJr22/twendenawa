<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class motorista extends Model
{
    protected $table = 'motoristas';

    protected $fillable = [
        'foto',
        'nome',
        'DataNascimento',
        'endereco',
        'BI',
        'sexos_id',
        'turnos_id',
        'users_id',
        'estado',
    ];

    protected $cast = [
        'DataNascimento' => 'date',
    ];

    public function sexos(){
        return $this->belongsTo('app\Models\sexo');
    }

    public function turnos(){
        return $this->belongsTo('app\Models\turno');
    }

    public function users(){
        return $this->belongsTo('app\Models\User');
    }

    public function carteiras(){
        return $this->hasOne('app\Models\carteira');
    }

    public function DadosViagems(){
        return $this->hasMany('app\Models\DadosViagem');
    }

}
