<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class escolas_motoristas extends Model
{
    protected $table = 'escolas_motoristas';

    protected $fillable = [
        'motoristas_id',
        'escolas_id'
    ];

    public function escola(){
        return $this->belongsTo(escola::class,'escolas_id');
    }

    public function motorista(){
        return $this->belongsTo(motorista::class,'motoristas_id');
    }
}
