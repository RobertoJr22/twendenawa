<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class municipio extends Model
{
    protected $table = 'municipios';

    protected $fillable =[
        'nome'
    ];

    public function distritos(){
        return $this->hasMany('app\Models\distrito');
    }
}
