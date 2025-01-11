<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class VerificarEstadoUsuario
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está logado
        if (Auth::check()) {
            $user = Auth::user();

            // Se o estado do usuário for 0 (desativado), desconecte-o e redirecione para o login
            if ($user->estado == 0) {
                Auth::logout();  // Desconecta o usuário
                return redirect()->route('login')->withErrors(['error' => 'Sua conta foi desativada.']);
            }
        }

        return $next($request);  // Continua com a requisição se o estado for 1
    }
}
