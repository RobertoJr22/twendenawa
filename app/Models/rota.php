<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rota extends Model
{
    protected $table = 'rotas';

    protected $fillable =[
        'nome',
        'PontoA',
        'PontoB',
        'escolas_id',
    ];

    public function veiculos(){
        return $this->belongsToMany(veiculo::class,'rotas_veiculos');
    }
    public function motoristas(){
        return $this->belongsToMany(motorista::class,'motoristas_rotas');
    }

    public function estudantes(){
        return $this->belongsToMany(estudante::class,'estudantes_rotas');
    }
    public function escola(){
        return $this->belongsTo(escola::class,'escolas_id');
    }
}
