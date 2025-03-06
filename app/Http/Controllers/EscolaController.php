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
use App\Models\escolas_motoristas;
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
            
            return redirect()->back()->with('alert', 'Área não permitida');
        }
    }
    
    public function CadastrarRota(Request $request)
    {
        $user = Auth::user();
        $escola = $user->escola;
    
        $nome = $request->input('nome');
        $pontoA = $request->input('pontoA');
        $pontoB = $request->input('pontoB');
    
        if ($user->tipo_usuario_id == 5) {
            if ($pontoA === $pontoB) {
                return redirect()->back()->with('error', 'Erro: Ponto A idêntico ao Ponto B.');
            }
    
            // Verificar se já existe uma rota com o mesmo PontoB para a mesma escola
            $rotaExistente = DB::table('rotas')
                ->where('escolas_id', $escola->id)
                ->where('pontoB', $pontoB)
                ->count();
    
            if ($rotaExistente > 0) {
                return redirect()->back()->with('error', 'Erro: Essa escola já tem essa rota.');
            }
    
            try {
                $rota = new Rota();
                $rota->nome = $nome;
                $rota->pontoA = $pontoA;
                $rota->pontoB = $pontoB;
                $rota->escolas_id = $escola->id;
                $rota->save();
    
                return redirect()->route('ListaRota')->with('sucess', 'Rota cadastrada com sucesso!');

            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Erro no cadastro: ' . $e->getMessage());
            }
        }
    
        return redirect()->back()->with('error', 'Ação não permitida.');
    }
    

    public function ListaRota(Request $request){
        $search = $request->input('search');
        $user = Auth::user();
        $escola = $user->escola;

        if($search){
            $rotas = rota::where('nome','like','%'.$search.'%')
            ->orwhere('PontoA','like','%'.$search.'%')
            ->orwhere('PontoB','like','%'.$search.'%')
            ->where('escolas_id',$escola->id)->get();
                    
        }else{
            $rotas = rota::where('escolas_id',$escola->id)->get();
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
            $escola=$user->escola;

            if($user->tipo_usuario_id == 5){
                $veiculo = new veiculo();
                $veiculo->modelos_id = $request->modelos_id;
                $veiculo->capacidade = $request->capacidade;
                $veiculo->Matricula = $request->matricula;
                $veiculo->VIN = $request->VIN;
                $veiculo->escolas_id = $escola->id;
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
            ->where('escolas_id', $user->escola->id)
            ->orWhereHas('modelo', function ($query) use ($search) {
                $query->where('nome', 'like', '%'.$search.'%');
            })
            ->orWhereHas('modelo.marcas', function ($query) use ($search) {
                $query->where('nome', 'like', '%'.$search.'%');
            })
            ->get();
        } else {
            // Caso não haja pesquisa, busca todos os veículos da escola
            $veiculos = veiculo::with(['modelo.marcas', 'rotas', 'motoristas.User'])
                ->where('escolas_id', $user->escola->id)
                ->get();
        }
    
        return view('Escola.Veiculo.ListaVeiculo', compact('veiculos'));
    }  
    
    public function ListaMotorista(){

        $user = Auth::user();
        $escola = $user->escola;

        try {
            $motoristas = DB::select('CALL GetMotoristasByEscola(?)', [$escola->id]);
    
            return view('Escola.Motorista.ListaMotorista', compact('motoristas'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao listar motoristas: ' . $e->getMessage());
        }
    }


    public function ExibirAdAssociar(){
        $user = Auth::user();

        $escola = $user->escola;

        $rotas = rota::where('escolas_id',$escola->id)->get();

        $veiculos = DB::select('CALL GetVeiculosDisponiveis(?)', [$escola->id]);                 

        $motoristas = escolas_motoristas::with(['motorista.turno', 'escola'])
        ->where('escolas_id', $escola->id)
        ->where('estado', 1)
        ->whereDoesntHave('motorista.motoristas_rotas_veiculos', function ($query) {
            $query->where('estado', 0);  // Verifica se não há registros com estado 0
        })
        ->get();    
                

        return view('Escola.Motorista.AdAssociar',compact('user','rotas', 'motoristas', 'veiculos'));
    }

    public function Associar(Request $request){

        if ($request->opcao == 1) {
            try {
                $motorista_id = $request->input('motoristas_id');
                $veiculo_id = $request->input('veiculos_id');
        
                // Verifica se o motorista já está associado a um veículo e rota com estado = 1
                $verif = motoristas_rotas_veiculos::where('motoristas_id', $motorista_id)
                    ->where('estado', 1)
                    ->first();
        
                if ($verif) {
                    return redirect()->back()->with('error', 'Este motorista já está associado a um veículo e uma rota.');
                }
        
                // Busca o turno do motorista informado
                $turno_id = DB::table('motoristas')
                    ->where('id', $motorista_id)
                    ->value('turnos_id');
        
                if (!$turno_id) {
                    return redirect()->back()->with('error', 'Motorista inserido não existe no sistema.');
                }
        
                // Verifica se já existe um motorista associado ao mesmo veículo no mesmo turno
                $existe = DB::table('motoristas_rotas_veiculos as mr')
                    ->join('motoristas as m', 'mr.motoristas_id', '=', 'm.id')
                    ->where('mr.veiculos_id', $veiculo_id)
                    ->where('mr.estado', 1)
                    ->where('m.turnos_id', $turno_id)
                    ->exists();
        
                if ($existe) {
                    return redirect()->back()->with('error', 'Já existe um motorista associado a este veículo no mesmo turno.');
                }
        
                // Realiza a associação de motorista, rota e veículo
                motoristas_rotas_veiculos::create([
                    'motoristas_id' => $motorista_id,
                    'veiculos_id' => $veiculo_id,
                    'rotas_id' => $request->input('rotas_id')
                ]);
        
                // Atualiza o estado do motorista na tabela escolas_motoristas
                escolas_motoristas::where('estado', 1)
                    ->where('motoristas_id', $motorista_id)
                    ->update(['estado' => 2]);
        
                return redirect()->route('ListaMotorista')->with('success', 'Associação feita com sucesso.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Erro ao associar motorista, rota e veículo: ' . $e->getMessage());
            }        

        }elseif($request->opcao == 2){

            $motorista = motorista::where('BI',$request->BI)->first();

            if($motorista){
                $verif = escolas_motoristas ::where('motoristas_id',$motorista->id)
                                            ->wherein('estado',[1,2])->first();
                if($verif){
                    return redirect()->back()->with('erro','Motorista ja está associado à essa escola');
                }else{
                    $verifestado0 = escolas_motoristas ::where('motoristas_id',$motorista->id)
                                            ->where('estado',0)->first();
                    if($verifestado0){
                        $verifestado0->update(['estado'=>1]);
                        return redirect()->route('ListaMotorista')->with('sucess','Motorista adicionado');
                    }else{
    
                        $escola = Auth::user()->escola;
                        if (!$escola) {
                            return redirect()->back()->with('error', 'Escola não encontrada para o usuário.');
                        }
    
                        $conexao = new escolas_motoristas();
    
                        $conexao->motoristas_id = $motorista->id;
                        $conexao->escolas_id = $escola->id;
                        $conexao->save();
    
                        return redirect()->route('ListaMotorista')->with('sucess','Motorista adicionado');
                    }
                }
            }else{
                return redirect()->back()->with('error','Motorista não encontrado');
            }

        }
    }


}
