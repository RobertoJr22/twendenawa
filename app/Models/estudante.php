<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class estudante extends Model
{
    protected $table = 'estudantes';

    protected $fillable = [
        'users_id',
        'foto',
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

    public function rotas(){
        return $this->belongsToMany(rota::class,'estudantes_rotas');
    }

    public function responsavels(){
        return $this->belongsToMany(responsavel::class,'estudantes_responsavels');
    }

    public function sexo(){
        return $this->belongsTo(sexo::class,'sexos_id');
    }

    public function turno(){
        return $this->belongsTo(turno::class,'turnos_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'users_id');
    }

    public function DadosViagems(){
        return $this->hasMany(DadosViagem::class,'estudantes_id');
    }
}
