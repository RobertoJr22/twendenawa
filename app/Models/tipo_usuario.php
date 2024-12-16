<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tipo_usuario extends Model
{

    // O nome da tabela (opcional se a tabela for o nome padrão plural)
    protected $table = 'tipo_usuarios';

    // Definir os campos que podem ser atribuídos em massa
    protected $fillable = [
        'nome',
        'estado',
    ];

    // Relacionamento com o modelo User
    public function users()
    {
        return $this->hasMany(User::class, 'tipo_usuarios_id');
    }
}
