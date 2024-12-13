<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class responsavel extends Model
{
    protected $table = 'responsavels';

    protected $fillable = [
        'nome',
        'DataNascimento',
        'foto',
        'endereco',
        'users_id',
        'sexos_id',
        'estado',
    ];

    protected $casts = [
        'DataNascimento' => 'date',
    ];


    public function sexos(){
        return $this->belongsTo('app\Models\sexo');
    }

    public function users(){
        return $this->belongsTo('app\Models\User');
    }
}
