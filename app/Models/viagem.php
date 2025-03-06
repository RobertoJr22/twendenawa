<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class viagem extends Model
{

    protected $table = 'viagems'; // Define a tabela caso o Laravel não reconheça o nome no plural

    protected $fillable = [
        'motoristas_id',
        'estado',
    ];

    public function motorista()
    {
        return $this->belongsTo(Motorista::class, 'motoristas_id');
    }

    public function DadosViagems(){
        return $this->hasMany(DadosViagem::class,'viagems_id');
    }
}
