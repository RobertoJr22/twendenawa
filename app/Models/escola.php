<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class escola extends Model
{
    protected $table = 'escolas';

    protected $fillable = [
        'nome',
        'bairros_id',
        'users_id',
        'estado',
    ];

    public function users(){
        return $this->belongsTo('app\Models\User');
    }

    public function bairros(){
        return $this->belongsTo('app\Models\bairro');
    }
}
