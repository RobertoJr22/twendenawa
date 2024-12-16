<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class bairro extends Model
{
    protected $table = 'bairros';

    protected $fillable = [
        'nome',
        'distrito_id',
        'estado',
    ];

    public function escolas(){
        return $this->hasMany(escola::class,'bairros_id');
    }
}
