<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class responsavel extends Model
{
    protected $table = 'responsavels';

    protected $fillable = [
        'id',
        'nome',
        'DataNascimento',
        'foto',
        'telefone',
        'endereco',
        'sexos_id',
        'estado',
    ];

    protected $incrementing = false;

    protected $keyType = 'unsignedBigInteger';

    protected $casts = [
        'DataNascimento' => 'date',
    ];


    public function sexos(){
        return $this->belongsTo(sexo::class,'sexos_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'id','id');
    }
}
