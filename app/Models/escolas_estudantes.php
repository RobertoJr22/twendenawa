<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class escolas_estudantes extends Model
{
    protected $table = 'escolas_estudantes'; // Nome da tabela

    protected $fillable = [
        'escolas_id',
        'estudantes_id',
        'estado',
    ];

    // Relação com Escola
    public function escola()
    {
        return $this->belongsTo(Escola::class, 'escolas_id');
    }

    // Relação com Estudante
    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudantes_id');
    }
}
