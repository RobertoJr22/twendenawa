<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tipo_usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Exibir o formulário de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Processar o login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Redirecionar para o home baseado no tipo de usuário
            $user = Auth::user();
            if ($user->tipo_usuario_id == 1) { // Exemplo: Admin
                return redirect()->route('TelaAdmin');
            } elseif ($user->tipo_usuario_id == 2) { // Exemplo: Estudante
                return redirect()->route('TelaEstudante');
            } elseif ($user->tipo_usuario_id == 3) { // Exemplo: Estudante
                return redirect()->route('TelaMotorista');
            } elseif ($user->tipo_usuario_id == 4) { // Exemplo: Estudante
                return redirect()->route('TelaResponsavel');
            }
        }

        return back()->withErrors(['email' => 'As credenciais não coincidem com nossos registros.']);
    }

    // Exibir o formulário de registro
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Função de logout
    public function logout(Request $request)
    {
        Auth::logout(); // Faz o logout do usuário

        $request->session()->invalidate(); // Invalida a sessão
        $request->session()->regenerateToken(); // Gera um novo token CSRF

        return redirect()->route('login'); // Redireciona para a página de login
    }

    // Processar o registro
    public function register(Request $request)
    {
        $request->validate([
            //'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'tipo_usuario_id' => 'required|exists:tipo_usuarios,id', // Garantir que o tipo de usuário seja válido
        ]);

        $user = User::create([
            //'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Criptografando a senha
            'tipo_usuario_id' => $request->tipo_usuario_id,
        ]);

        Auth::login($user); // Loga o usuário após o registro

        // Redireciona com base no tipo de usuário
        if ($user->tipo_usuario_id == 1) {
            return redirect()->route('Dashboard.Dash');
        } elseif ($user->tipo_usuario_id == 2) {
            return redirect()->route('TelaEstudante');
        } elseif($user->tipo_usuario_id == 3) {
            return redirect()->route('TelaMotorista');
        } elseif($user->tipo_usuario_id == 4) {
            return redirect()->route('TelaResponsavel');
        }
    }
}
