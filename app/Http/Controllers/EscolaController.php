<?php

namespace App\Http\Controllers;

use App\Models\escola;
use App\Models\rota;
use App\Models\marca;
use App\Models\modelo;
use App\Models\motorista;
use app\Models\motoristas_rotas_veiculos;
use App\Models\veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EscolaController extends Controller
{ 
    public function index(){
        return view('Escola.MainEscola');
    }
    
    //Rotas
    public function ExibirCadastrarRota()
    {
        $user = Auth::user();

        if($user->tipo_usuario_id == 5) {
            $count = DB::table('rotas')
            ->join('escolas', 'rotas.escolas_id','=','escolas.id')
            ->join('users','users.id','=','escolas.users_id')
            ->where('users.id', $user->id)
            ->count();

            if($count == 0){
                $count = 1;
            }else{
                $count += 1;
            }

            $NomeRota = $user->name.$count;
            return view('Escola.Rota.CadastrarRota', compact('user','NomeRota'));

        } else {
            
            return redirect()->route('alguma-rota')->with('alert', 'Área não permitida');
        }
    }
    
    public function CadastrarRota(Request $request){
        $user = Auth::user();

        if($user->tipo_usuario_id == 5){

            try{
                $rota = new rota();
                $rota->nome = $request->nome;
                $rota->PontoA = $request->pontoA;
                $rota->PontoB = $request->pontoB;
                $rota->escolas_id = $user->id;
                $rota->save();

            }catch(\Exception $e){
                return redirect()->back()->with('error','Ocorreu algum erro no cadastro: '.$e->getMessage());
            }   
            return redirect()->route('ListaRota')->with('sucess','Rota cadastrada com sucesso');
        }

    }
    public function ListaRota(Request $request){
        $search = $request->input('search');
        $user = Auth::user();

        if($search){
            $rotas = rota::where('nome','like','%'.$search.'%')
            ->orwhere('PontoA','like','%'.$search.'%')
            ->orwhere('PontoB','like','%'.$search.'%')
            ->where('escolas_id',$user->id)->get();
                    
        }else{
            $rotas = rota::where('escolas_id',$user->id)->get();
        }
        

        return view('Escola.Rota.ListaRota', compact('rotas'));
    }

    //Veiculo
    public function ExibirCadastrarVeiculo()
    {
        $user = Auth::user();
        $marcas = marca::all();
    
        // Pega a escola associada ao usuário logado
        $escola = escola::where('users_id', $user->id)->first();
    
        // Verifica se a escola foi encontrada
        if ($escola) {
            // Pega as rotas associadas à escola
            $rotas = rota::where('escolas_id', $escola->id)->get();
            return view('Escola.Veiculo.CadastrarVeiculo', compact('rotas','marcas'));
        } else {
            // Caso a escola não exista
            return redirect()->back()->with('alert', 'Escola não encontrada.');
        }
    }
    

    public function CadastrarVeiculo(Request $request){
        DB::beginTransaction();
        try{
            $user = Auth::user();
            if($user->tipo_usuario_id == 5){
                $veiculo = new veiculo();
                $veiculo->modelos_id = $request->modelos_id;
                $veiculo->capacidade = $request->capacidade;
                $veiculo->Matricula = $request->matricula;
                $veiculo->VIN = $request->VIN;
                $veiculo->escolas_id = $user->id;
                $veiculo->save();               
            }
            DB::commit();
            return redirect()->back()->with('sucess','Veiculo cadastrado com sucesso');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error','Erro ao cadastrar o veiculo');
        }
    }

    public function getModelos($marcaId)
    {
        $modelos = Modelo::where('marcas_id', $marcaId)->get();

        if ($modelos->isEmpty()) {
            return response()->json(['message' => 'Nenhum modelo encontrado'], 404);
        }

        return response()->json($modelos);
    }

    public function ListaVeiculo(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();
    
        if ($search) {
            $veiculos = veiculo::where('VIN', 'like', '%'.$search.'%')
            ->orWhere('capacidade', 'like', '%'.$search.'%')
            ->orWhere('Matricula', 'like', '%'.$search.'%')
            ->where('estado', 1)
            ->where('escolas_id', $user->id)
            ->orWhereHas('modelo', function ($query) use ($search) {
                $query->where('nome', 'like', '%'.$search.'%');
            })
            ->orWhereHas('modelo.marcas', function ($query) use ($search) {
                $query->where('nome', 'like', '%'.$search.'%');
            })
            ->get();
        } else {
            // Caso não haja pesquisa, busca todos os veículos da escola
            $veiculos = veiculo::with(['modelo.marcas', 'rotas', 'motoristas'])
                ->where('escolas_id', $user->id)
                ->get();
        }
    
        return view('Escola.Veiculo.ListaVeiculo', compact('veiculos'));
    }
    
    

   

    public function Estudante(){
        return view('Escola.Estudante');
    }

    public function Motorista(){
        return view('Escola.Motorista');
    }

    public function Responsavel(){
        return view('Escola.Responsavel');
    }

    public function Viatura(){
        return view('Escola.Viatura');
    }
}
