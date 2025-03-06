<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DadosViagem extends Model
{
    protected $table = 'dados_viagems';

    protected $fillable = [
        'estudantes_id',
        'viagems_id',
        'relatorio',
        'estado',
    ];

    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudantes_id');
    }

    public function viagem()
    {
        return $this->belongsTo(Viagem::class, 'viagems_id');
    }

}
