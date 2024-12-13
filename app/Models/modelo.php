<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class modelo extends Model
{
    protected $table = 'modelos';

    protected $fillable =[
        'nome',
        'marcas_id'
    ];

    public function marcas(){
        return $this->belongsTo('app\Models\marca');
    }
    public function veiculos(){
        return $this->hasMany('app\Models\veiculo');
    }
}
