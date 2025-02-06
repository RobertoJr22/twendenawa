<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class veiculo extends Model
{
    protected $table = 'veiculos';

    protected $fillable = [
        'escolas_id',
        'modelo_id',
        'Matricula',
        'estado',
        'VIN',
        'estado',
        'capacidade',
    ];


    public function rotas()
    {
        return $this->belongsToMany(Rota::class, 'motoristas_rotas_veiculos', 'veiculos_id', 'rotas_id');
    }
    
    public function motoristas()
    {
        return $this->belongsToMany(Motorista::class, 'motoristas_rotas_veiculos', 'veiculos_id', 'motoristas_id');
    }
    

    public function modelo(){
        return $this->belongsTo(modelo::class,'modelos_id');
    }

    public function escola(){
        return $this->belongsTo(escola::class,'escolas_id');
    }

    public function motoristas_rotas_veiculos()
    {
        return $this->hasMany(motoristas_rotas_veiculos::class, 'veiculos_id');
    }

}
