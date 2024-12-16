<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class modelo extends Model
{
    protected $table = 'modelos';

    protected $fillable =[
        'nome',
        'marcas_id',
        'estado',
    ];

    public function marcas(){
        return $this->belongsTo(marca::class,'marcas_id');
    }
    public function veiculos(){
        return $this->hasMany(veiculo::class,'modelos_id');
    }
}
