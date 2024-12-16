<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class motorista extends Model
{
    protected $table = 'motoristas';

    protected $fillable = [
        'id',
        'foto',
        'nome',
        'DataNascimento',
        'endereco',
        'BI',
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
        return $this->belongsTo(turno::class,'turnos_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'id','id');
    }

    public function carteiras(){
        return $this->hasOne(carteira::class,'motoristas_id');
    }

    public function DadosViagems(){
        return $this->hasMany(DadosViagem::class,'motoristas_id');
    }

}
