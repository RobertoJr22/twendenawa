<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class responsavel extends Model
{
    use Notifiable;

    protected $table = 'responsavels';

    protected $fillable = [
        'users_id',
        'DataNascimento',
        'foto',
        'BI',
        'telefone',
        'endereco',
        'sexos_id',
        'estado',
    ];

    protected $casts = [
        'DataNascimento' => 'date',
    ];


    public function sexo(){
        return $this->belongsTo(sexo::class,'sexos_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'users_id');
    }

    public function estudantes(){
        return $this->belongsToMany(estudante::class,'estudantes_responsavels');
    }
}
