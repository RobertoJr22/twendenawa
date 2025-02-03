<?php

namespace App\Http\Controllers;
use App\Models\escola;
use App\Models\rota;
use App\Models\marca;
use App\Models\modelo;
use App\Models\motorista;
use App\Models\motoristas_rotas_veiculos;
use App\Models\veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreMotoristaRequest;
use App\Models\carteira;
use App\Models\turno;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
    
    public function ListaMotorista(){

        $user = Auth::user();

        $motoristas = motoristas_rotas_veiculos::with([
            'rota.escola',   // Pega a escola associada à rota
            'veiculo.modelo.marcas', // Pega a marca do veículo via modelo
            'motorista.carteira', // Pega a carteira do motorista
            'motorista.user',
            'motorista.turno'
        ])
        ->whereHas('rota', function ($query) use ($user) {
            $query->where('escolas_id', $user->id);
        })
        ->whereIn('estado', [1, 2]) // Adiciona a condição de estado ser 1 ou 2
        ->get();

        
        return view('Escola.Motorista.ListaMotorista',compact('motoristas','user'));
    }


    public function ExibirAdAssociar(){
        $user = Auth::user();

        $escola = $user->escola;

        $rotas = rota::where('escolas_id',$escola->id)->get();


        $veiculos = motoristas_rotas_veiculos::with(['veiculo.modelo.marcas'])
                    ->where('estado', '!=', 1)  // Filtra onde o estado é diferente de 1
                    ->whereHas('veiculo', function($query) use ($escola) {
                        $query->where('escolas_id', $escola->id);  // Condição para veiculos onde escolas_id é igual ao id do usuário
                    })
                    ->get();
    
                        

        $motoristas = motoristas_rotas_veiculos::with([
            'rota.escola',         // Relacionamento com a escola via rota
            'veiculo',             // Relacionamento com o veículo
            'veiculo.modelo.marcas', // Relacionamento com o modelo e marcas do veículo
            'motorista.carteira',  // Relacionamento com a carteira do motorista
            'motorista.user',       // Relacionamento com o usuário do motorista
            'Motorista.turno'
        ])
        ->where('estado', 2)  // Filtrando onde o estado é igual a 2
        ->whereHas('rota', function($query) use ($escola) {
            $query->where('escolas_id', $escola->id);  // Filtrando pela condição escolas_id igual ao $user->id
        })
        ->get();
                
        

        return view('Escola.Motorista.AdAssociar',compact('user','rotas', 'motoristas', 'veiculos'));
    }
}
