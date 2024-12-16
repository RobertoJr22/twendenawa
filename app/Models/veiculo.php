<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class veiculo extends Model
{
    protected $table = 'veiculos';

    protected $fillable = [
        'escolas_id',
        'modelo_id',
        'Matricula',
        'estado',
        'VIN',
        'estado',
        'capacidade',
    ];

    public function modelo(){
        return $this->belongsTo(modelo::class,'modelos_id');
    }

    public function escola(){
        return $this->belongsTo(escola::class,'escolas_id');
    }
}
