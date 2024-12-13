<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class marca extends Model
{
    protected $table = 'marcas';

    protected $fillable = [
        'nome',
    ];


    public function modelos(){
        return $this->hasMany('app\Models\modelo');
    }
}
