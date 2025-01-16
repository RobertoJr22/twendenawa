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

    public function veiculos()
    {
        return $this->belongsToMany(Veiculo::class, 'motoristas_rotas_veiculos', 'rotas_id', 'veiculos_id');
    }
    
    public function motoristas()
    {
        return $this->belongsToMany(Motorista::class, 'motoristas_rotas_veiculos', 'rotas_id', 'motoristas_id');
    }
    

    public function estudantes(){
        return $this->belongsToMany(estudante::class,'estudantes_rotas');
    }
    public function escola(){
        return $this->belongsTo(escola::class,'escolas_id');
    }
}
