<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class estudante extends Model
{
    protected $table = 'motoristas';

    protected $fillable = [
        'foto',
        'nome',
        'DataNascimento',
        'endereco',
        'telefone',
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

    public function DadosViagems(){
        return $this->hasMany('app\Models\DadosViagem');
    }
}
