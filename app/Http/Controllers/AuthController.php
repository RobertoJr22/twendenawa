<?php

namespace App\Http\Controllers;

use App\Models\estudante;
use App\Models\User;
use App\Models\responsavel;
use App\Models\Tipo_usuario;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Exibir o formulário de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Exibir o Tela de selecao de tipo de registo
    public function SelecaoRegisto(){
        return view('auth.Selecao');
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
            // Obter o usuário autenticado
            $user = Auth::user();
    
            // Verificar se o estado do usuário é 1 (ativo)
            if ($user->estado != 1 && $user->estado == 0) {
                Auth::logout(); // Desconectar o usuário
                return redirect()->route('login')->withErrors(['error' => 'Sua conta foi desativada.']);
            }
    
            // Regenerar a sessão após o login
            $request->session()->regenerate();
    
            // Redirecionar para a tela adequada dependendo do tipo de usuário
            if ($user->tipo_usuario_id == 1) { // Exemplo: Admin
                return redirect()->route('TelaAdmin');
            } elseif ($user->tipo_usuario_id == 2) { // Exemplo: Estudante
                return redirect()->route('TelaEstudante');
            } elseif ($user->tipo_usuario_id == 3) { // Exemplo: Motorista
                return redirect()->route('TelaMotorista');
            } elseif ($user->tipo_usuario_id == 4) { // Exemplo: Responsavel
                return redirect()->route('TelaResponsavel');
            } elseif ($user->tipo_usuario_id == 5) { // Exemplo: Escola
                return redirect()->route('TelaEscola');
            }
        }
    
        return back()->withErrors(['email' => 'As credenciais não coincidem com nossos registros.']);
    }
    

    // Função de logout

    public function logout(Request $request)
    {
        Auth::logout(); // Faz o logout do usuário

        $request->session()->invalidate(); // Invalida a sessão
        $request->session()->regenerateToken(); // Gera um novo token CSRF

        return redirect()->route('login'); // Redireciona para a página de login
    }

    // Exibir o formulário de registro

    public function showResponsavelForm()
    {
        return view('auth.ResponsavelRegister');
    }

    public function showEstudanteForm()
    {
        return view('auth.EstudanteRegister');
    }


    // Processar o registro

  /*  public function register(Request $request)
    {
        DB::beginTransaction();
        try{
            // Valida campos comuns a todos os usuários
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
                'tipo_usuario_id' => 'required|exists:tipo_usuarios,id',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            // Validação condicional com base no tipo de usuário
            if ($request->tipo_usuario_id == 2) { // Estudante
                $request->validate([
                    'DataNascimento' => 'required|date',
                    'endereco' => 'required|string|max:255',
                    'telefone' => 'required|string|max:15',
                    'sexos_id' => 'required|exists:sexos,id',
                    'turnos_id' => 'required|exists:turnos,id',
                ]);
            } elseif ($request->tipo_usuario_id == 4) { // Responsável
                $request->validate([
                    'DataNascimento' => 'required|date',
                    'BI' => 'required|string|max:14',
                    'telefone' => 'required|string|max:15',
                    'endereco' => 'required|string|max:255',
                    'sexos_id' => 'required|exists:sexos,id',
                ]);
            }

            $user = User::create([
                'name'=>$request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Criptografando a senha
                'tipo_usuario_id' => $request->tipo_usuario_id,
            ]);



            // Verifica se a foto foi enviada
            $path = $request->hasFile('foto') ? $request->file('foto')->store('fotos', 'public') : null;
            dd($path);


            //Criar um perfil de acordo o tipo de usuario e respectiva tabela
            if ($user->tipo_usuario_id == 2) {

                estudante::create([
                    'users_id'=>$user->id,
                    'foto'=>$path,
                    'DataNascimento'=>$request->DataNascimento,
                    'endereco'=>$request->endereco,
                    'telefone'=>$request->telefone,
                    'sexos_id'=>$request->sexos_id,
                    'turnos_id'=>$request->turnos_id,
                ]);
            }

            elseif($user->tipo_usuario_id == 3) {

            }

            elseif($user->tipo_usuario_id == 4) {

                responsavel::create([
                    'users_id'=>$user->id,
                    'foto'=>$path,
                    'DataNascimento'=>$request->DataNascimento,
                    'BI'=>$request->BI,
                    'telefone'=>$request->telefone,
                    'endereco'=>$request->endereco,
                    'sexos_id'=>$request->sexos_id,
                ]);

            }

            elseif($user->tipo_usuario_id == 5) {

            }
                dd($user);
            DB::commit();

        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao cadastrar: ' . $e->getMessage());
        }


        Auth::login($user); // Loga o usuário após o registro

        // Redireciona com base no tipo de usuário
        if ($user->tipo_usuario_id == 1) {
            return redirect()->route('TelaAdmin');
        } elseif ($user->tipo_usuario_id == 2) {
            return redirect()->route('TelaEstudante');
        } elseif($user->tipo_usuario_id == 3) {
            return redirect()->route('TelaMotorista');
        } elseif($user->tipo_usuario_id == 4) {
            return redirect()->route('TelaResponsavel');
        } elseif($user->tipo_usuario_id == 5) {
            return redirect()->route('TelaEscola');
        }
    }*/
}