<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\estudante;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreStudentRequest;
use Illuminate\Support\Facades\Auth;

class EstudanteController extends Controller
{
    public function index(){
        return view('Estudante.CadastrarEstudante');
    }

    public function MainEstudante(){
        $user = Auth::user();
        $estudante = $user->estudante;
        $turno = $estudante->turno;
        $rota = DB::table('rotas as t1')
                    ->join('estudantes_rotas as t2','t2.rotas_id','=','t1.id')
                    ->where('t2.estudantes_id','=',$estudante->id)
                    ->where('t2.estados','=',1)
                    ->select('t1.nome as nome','t1.PontoA as PontoA', 't1.PontoB as PontoB')->first();
        $responsaveis = DB::table('Responsavels as t1')
                    ->join('users as t2', 't2.id', '=', 't1.users_id')
                    ->join('estudantes_responsavels as t3', 't3.responsavels_id', '=', 't1.id')
                    ->where('t3.estudantes_id', '=', $estudante->id)
                    ->where('t3.estado', '=', 1)
                    ->select('t2.name as nome')
                    ->get();
        $escola = DB::table('escolas as t1')
                    ->join('rotas as t2','t2.escolas_id','=','t1.id')
                    ->join('estudantes_rotas as t3','t3.rotas_id','=','t2.id')
                    ->join('users as t4','t4.id','=','t1.users_id')
                    ->join('bairros as t5','t5.id','=','t1.bairros_id')
                    ->join('distritos as t6','t6.id','=','t5.distritos_id')
                    ->join('municipios as t7','t7.id','=','t6.municipios_id')
                    ->where('t3.estados','=',1)
                    ->where('t3.estudantes_id','=',$estudante->id)
                    ->select('t4.name as nome', 't1.telefone as telefone', 't4.email as email', 't7.nome as municipio', 't5.nome as bairro')
                    ->first();
                

        return view('Estudante.MainEstudante', compact('estudante','turno','rota', 'user', 'escola','responsaveis'));
    }

    public function DetalhesViagem(){
        return view('Estudante.DetalhesViagem');
    }

    public function store(StoreStudentRequest $request){

        DB::beginTransaction();
        try{
            $imagePath = null;
            if ($request->hasFile('foto')) 
            {
                $imagePath = $request->file('foto')->store('avatares', 'public');
            } 
            
            $user = New User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password); 
            $user->tipo_usuario_id = $request->tipo_usuario_id;
            $user->save();
            
            $estudante = New estudante(); 
            $estudante->users_id = $user->id;
            $estudante->foto = $imagePath;
            $estudante->DataNascimento = $request->DataNascimento;
            $estudante->endereco = $request->endereco;
            $estudante->telefone = $request->telefone;
            $estudante->sexos_id = $request->sexos_id;
            $estudante->turnos_id = $request->turnos_id;
            $estudante->save();
            
            DB::commit();

            Auth::login($user);
            return redirect()->route('TelaEstudante')->with('sucess','Bem vindo ao Twendenawa');
            
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error','Erro ao cadastrar o estudante'.$e->getMessage());
        }
    }


    public function InfoEstudante(){
        return view('Estudante.InfoEstudante');
    }

    public function SelecaoEstudante(){
        return view('Estudante.SelecaoEstudante');
    }
}