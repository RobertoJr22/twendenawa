<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class motoristas_rotas_veiculos extends Model
{
    protected $table = 'motoristas_rotas_veiculos';

    protected $fillable = [
        'veiculos_id',
        'motoristas_id',
        'rotas_id',
        'estado'
    ];

        // Relacionamento com Motorista
        public function motorista()
        {
            return $this->belongsTo(Motorista::class, 'motoristas_id');
        }
    
        // Relacionamento com Veículo
        public function veiculo()
        {
            return $this->belongsTo(Veiculo::class, 'veiculos_id');
        }
    
        // Relacionamento com Rota
        public function rota()
        {
            return $this->belongsTo(Rota::class, 'rotas_id');
        }
}
