<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class motorista extends Model
{
    protected $table = 'motoristas';

    protected $fillable = [
        'users_id',
        'foto',
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


    public function rotas()
    {
        return $this->belongsToMany(Rota::class, 'motoristas_rotas_veiculos', 'motoristas_id', 'rotas_id');
    }
    
    public function veiculos()
    {
        return $this->belongsToMany(Veiculo::class, 'motoristas_rotas_veiculos', 'motoristas_id', 'veiculos_id');
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

    public function carteira(){
        return $this->hasOne(carteira::class,'motoristas_id');
    }

    public function DadosViagems(){
        return $this->hasMany(DadosViagem::class,'motoristas_id');
    }

}
