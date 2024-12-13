<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class bairro extends Model
{
    protected $table = 'bairros';

    protected $fillable = [
        'nome',
        'distrito_id',
    ];

    public function escolas(){
        return $this->hasMany('app\Models\escola');
    }
}
