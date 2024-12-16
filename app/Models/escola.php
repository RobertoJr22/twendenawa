<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class escola extends Model
{
    protected $table = 'escolas';

    protected $fillable = [
        'id',
        'nome',
        'bairros_id',
        'telefone',
        'estado',
    ];

    public function users(){
        return $this->belongsTo(User::class,'id','id');
    }

    public function bairros(){
        return $this->belongsTo(bairro::class,'bairros_id');
    }

    public function veiculos(){
        return $this->hasMany(veiculo::class,'escolas_id');
    }
}
