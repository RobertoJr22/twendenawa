<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email', 
        'password', 
        'tipo_usuario_id', // Campo relacionado ao tipo de usuário
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relacionamento com o TipoUsuario
    public function tipoUsuarios(){
        return $this->belongsTo(tipo_usuario::class, 'tipo_usuario_id');
    }

    public function escolas(){
        return $this->hasOne(escola::class, 'id','id');
    }

   // No modelo User
public function responsavel()
{
    return $this->hasOne(Responsavel::class, 'users_id'); 
}


    public function motoristas(){
        return $this->hasOne(motorista::class, 'id','id');
    }

    public function estudantes(){
        return $this->hasOne(estudante::class, 'id','id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts():array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}