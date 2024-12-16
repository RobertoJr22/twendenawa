<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class marca extends Model
{
    protected $table = 'marcas';

    protected $fillable = [
        'nome',
        'estado',
    ];


    public function modelos(){
        return $this->hasMany(modelo::class,'marcas_id');
    }
}
