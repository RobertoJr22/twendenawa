<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class estudante extends Model
{
    protected $table = 'motoristas';

    protected $fillable = [
        'id',
        'foto',
        'nome',
        'DataNascimento',
        'endereco',
        'telefone',
        'sexos_id',
        'turnos_id',
        'estado',
    ];

    protected $cast = [
        'DataNascimento' => 'date',
    ];

    public function sexos(){
        return $this->belongsTo(sexo::class,'sexos_id');
    }

    public function turnos(){
        return $this->belongsTo(turno::class,'turnos');
    }

    public function users(){
        return $this->belongsTo(User::class,'id','id');
    }

    public function DadosViagems(){
        return $this->hasMany(DadosViagem::class,'estudantes_id');
    }
}
