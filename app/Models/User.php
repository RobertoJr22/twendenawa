<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email', 
        'password', 
        'tipo_usuario_id', // Campo relacionado ao tipo de usuário
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relacionamento com o TipoUsuario
    public function tipoUsuarios(){
        return $this->belongsTo(tipo_usuario::class, 'tipo_usuario_id');
    }

    public function escola(){
        return $this->hasOne(escola::class, 'users_id');
    }

    public function responsavel(){
        return $this->hasOne(Responsavel::class, 'users_id'); 
    }

    public function motorista(){
        return $this->hasOne(motorista::class, 'users_id');
    }

    public function estudante(){
        return $this->hasOne(estudante::class, 'users_id');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Método para gerar um username único automaticamente
    public static function generateUniqueUsername($name)
    {
        // Converte o nome para um formato adequado (ex: "João Silva" -> "joao_silva")
        $baseUsername = Str::slug($name, '_');
        $username = $baseUsername;
        $counter = 1;
        
        // Enquanto o username já existir, adiciona um sufixo numérico
        while (self::where('username', $username)->exists()) {
            $username = $baseUsername . '_' . $counter;
            $counter++;
        }
        
        return $username;
    }
}
