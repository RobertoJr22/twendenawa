<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class veiculo extends Model
{
    protected $table = 'veiculos';

    protected $fillable = [
        'modelo_id',
        'Matricula',
        'estado'
    ];

    public function modelo(){
        return $this->belongsTo('app\Models\modelo');
    }
}
