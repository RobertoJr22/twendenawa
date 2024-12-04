<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class Helper
{
    // Método para pegar qualquer dado do usuário logado
    public static function DadosUsuario($field)
    {
        // Verifica se o usuário está autenticado e se o campo existe
        return Auth::check() ? Auth::user()->$field : null;
    }
}
