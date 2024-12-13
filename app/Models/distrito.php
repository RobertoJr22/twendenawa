<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class distrito extends Model
{
    protected $table = 'distritos';

    protected $fillable = [
        'nome',
        'municipios_id'
    ];

    public function municipios(){
        return $this->belongsTo('app\Models\municipio');
    }
}
