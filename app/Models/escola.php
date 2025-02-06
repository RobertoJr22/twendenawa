<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class escola extends Model
{
    protected $table = 'escolas';

    protected $fillable = [
        'users_id',
        'bairros_id',
        'telefone',
        'estado',
    ];

    public function motoristas()
    {
        return $this->belongsToMany(Rota::class, 'escolas_motoristas', 'motoristas_id', 'escolas_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'users_id');
    }

    public function bairro(){
        return $this->belongsTo(bairro::class,'bairros_id');
    }

    public function veiculos(){
        return $this->hasMany(veiculo::class,'escolas_id');
    }

    public function rotas(){
        return $this->hasMany(rota::class,'escolas_id');
    }
}
