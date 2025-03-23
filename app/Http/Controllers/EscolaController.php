<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\escola;
use App\Models\rota;
use App\Models\marca;
use App\Models\modelo;
use App\Models\motorista;
use App\Models\escolas_estudantes;
use App\Models\motoristas_rotas_veiculos;
use App\Models\veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreMotoristaRequest;
use App\Models\carteira;
use App\Models\escolas_motoristas;
use App\Models\estudantes_rotas;
use App\Models\turno;
use App\Models\User;
use App\Models\estudante;
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
            return redirect()->route('ListaVeiculo')->with('sucess','Veiculo cadastrado com sucesso');
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
        
       /* $veiculos = DB::table('veiculos as t2')
        ->leftJoin('motoristas_rotas_veiculos as t1', 't2.id', '=', 't1.veiculos_id')
        ->join('modelos as t3', 't3.id', '=', 't2.modelos_id')
        ->join('marcas as t4', 't4.id', '=', 't3.marcas_id')
        ->join('escolas as t5','t5.id','=','t2.escolas_id')
        ->select(
            't2.id',
            't2.Matricula',
            't4.nome as marca',
            't3.nome as modelo'
        )
        ->where(function ($query) {
            $query->whereNull('t1.veiculos_id') // Veículos que não estão na tabela t1
                  ->orWhere('t1.estado', 0); // Veículos que estão na tabela t1 com estado = 1
        })->where('t5.id',$escola->id)
        ->get();*/

        // Busca os motoristas associados à escola   


        $motoristas = DB::table('escolas_motoristas as t1')
        ->join('motoristas as t2', 't1.motoristas_id', '=', 't2.id')
        ->join('users as t3', 't2.users_id', '=', 't3.id')
        ->join('turnos as t4', 't2.turnos_id', '=', 't4.id')
        ->join('carteiras as t5', 't5.motoristas_id', '=', 't2.id')
        ->leftJoin('motoristas_rotas_veiculos as t6', function ($join) {
            $join->on('t6.motoristas_id', '=', 't1.motoristas_id')
                ->where('t6.estado', 0);
        })
        ->select(
            't2.id',
            't3.name as nome',
            't4.nome as turno',
            't5.NumeroCarta as carteira'
        )->where('t1.escolas_id', $escola->id)
        ->where('t1.estado', 1)
        ->get();

        return view('Escola.Motorista.AdAssociar',compact('user','rotas', 'motoristas', 'veiculos'));
    }

    public function Associar(Request $request){

        if ($request->opcao == 1) {
            try {
                $motoristaid = $request->input('motoristas_id');
                $veiculoid = $request->input('veiculos_id');
                $rotasId = $request->input('rotas_id');


                //Verificar se o motorista esta ativo no sistema
                $ativo = DB::table('motoristas as t1')
                ->join('users as t2','t2.id','=','t1.users_id')
                ->where('t1.id',$motoristaid)
                ->where('t2.estado',1)
                ->exists();

                if(!$ativo){
                    return redirect()->back()->with('error','Usuário está desativado ou inexistente');
                }
               
                // Busca o turno do motorista informado

                $turno_id = DB::table('motoristas')
                    ->where('id', $motoristaid)
                    ->value('turnos_id');
        
                if (!$turno_id) {
                    return redirect()->back()->with('error', 'Motorista está sem turno.');
                }
                
                // Verifica se já existe um motorista associado ao mesmo veículo no mesmo turno

                    $existe = DB::table('motoristas_rotas_veiculos as t1')
                    ->join('motoristas as t2', 't1.motoristas_id', '=', 't2.id')
                    ->where('t1.veiculos_id', $veiculoid)
                    ->where('t1.estado', 1)
                    ->where('t2.turnos_id', $turno_id)
                    ->exists();
        
                if ($existe) {
                    return redirect()->back()->with('error', 'Já existe um motorista associado a este veículo no mesmo turno.');
                }

                //Verifica se o motorista tem uma associacao ativa na escola

                $AssociacaoAtviva = DB::table('motoristas_rotas_veiculos as t1')
                ->join('veiculos as t2','t2.id','=','t1.veiculos_id')
                ->where('t1.motoristas_id',$motoristaid)
                ->where('t2.escolas_id',Auth::user()->escola->id)
                ->where('t1.estado',1)->exists();

                if ($AssociacaoAtviva) {
                    return redirect()->back()->with('error', 'Motorista já tem uma associação ativa, nessa instituição');
                }

                //Verifica se o motorista tem uma associacao ativa noutra escola

                $OutraEscola = DB::table('motoristas_rotas_veiculos')
                ->where('motoristas_id',$motoristaid)
                ->where('estado',1)->exists();

                if ($OutraEscola) {
                    return redirect()->back()->with('error', 'Motorista já tem associação ativa, noutra instituição');
                }
        
        
                // Realiza a associação de motorista, rota e veículo
                motoristas_rotas_veiculos::create([
                    'motoristas_id' => $motoristaid,
                    'veiculos_id' => $veiculoid,
                    'rotas_id' => $rotasId
                ]);
        
                // Atualiza o estado do motorista na tabela escolas_motoristas
                escolas_motoristas::where('estado', 1)
                    ->where('motoristas_id', $motoristaid)
                    ->update(['estado' => 2]);
        
                return redirect()->route('ListaMotorista')->with('sucess', 'Associação feita com sucesso.');

            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Erro ao associar motorista, rota e veículo: ' . $e->getMessage());
            }        

        }elseif($request->opcao == 2){

            $escola = Auth::user()->escola;

            $motorista = DB::table('motoristas as t1')
            ->join('users as t2','t2.id','=','t1.users_id')
            ->select('t1.id')
            ->where('t2.username',$request->username)->first();

            if(!$motorista){
                return redirect()->back()->with('error','Motorista não encontrado');
            }
            $existe = escolas_motoristas ::where('motoristas_id',$motorista->id)
                                        ->where('escolas_id',$escola->id)
                                        ->whereIn('estado',[1,2])->exists();
            if($existe){
                return redirect()->back()->with('error','Motorista ja está associado à essa escola');
            }
            
            $Desativo = escolas_motoristas ::where('motoristas_id',$motorista->id)
                                        ->where('escolas_id',$escola->id)
                                        ->where('estado',0)->first();
            if($Desativo){
                DB::table('escolas_motoristas')
                ->where('id',$Desativo->id)
                ->update(['estado'=> 3]);

                return redirect()->route('ListaMotorista')->with('sucess','Motorista adicionado');
            }

            $Outra = escolas_motoristas ::where('motoristas_id',$motorista->id)
                                ->whereIn('estado',[1,2])->exists();
            if($Outra){
                return redirect()->back()->with('error','Motorista ja está associado à outra escola');
            }

            $conexao = new escolas_motoristas();

            $conexao->motoristas_id = $motorista->id;
            $conexao->escolas_id = $escola->id;
            $conexao->estado = 3;
            $conexao->save();

            return redirect()->route('ListaMotorista')->with('sucess','Motorista adicionado');

        }
    }

    public function ListaEstudante(Request $request)
    {
        $escola = Auth::user()->escola;
    
        $busca = DB::table('users as t1')
            ->join('estudantes as t2', 't1.id', '=', 't2.users_id')
            ->join('turnos as t3', 't3.id', '=', 't2.turnos_id')
            ->join('estudantes_rotas as t4', 't4.estudantes_id', '=', 't2.id')
            ->join('rotas as t5', 't5.id', '=', 't4.rotas_id')
            ->select(
                't2.id',
                't1.name as nome',
                't1.email',
                't2.telefone',
                't5.nome as rota',
                't5.PontoA',
                't5.PontoB',
                't3.nome as turno'
            )
            ->where('t4.estados', 1)
            ->where('t5.escolas_id', $escola->id);
    
        // Adiciona a busca se houver input 'search'
        if ($request->filled('search')) {
            $search = $request->input('search');
            $busca->where(function ($q) use ($search) {
                $q->where('t1.name', 'like', "%$search%")
                  ->orWhere('t2.id', 'like', "%$search%");
            });
        }
    
        $busca = $busca->get();
    
        return view('Escola.Estudante.ListaEstudante', compact('busca', 'escola'));
    }
    public function ExibirCadastrarEstudante(){

        $rotas = rota::where('escolas_id',Auth::user()->escola->id)->get();
        
        return view('Escola.Estudante.CadastrarEstudante',compact('rotas'));
    }

    public function CadastrarEstudante(Request $request){

        $username = $request->input('username');
        $rotaId = (int) $request->input('rotasId');




        $estudante = DB::table('estudantes as t1')
        ->join('users as t2','t2.id','=','t1.users_id')
        ->select(
            't1.id'
        )
        ->where('t2.username',$username)
        ->where('t2.estado',1)->first();

        if(!$estudante){
            return redirect()->back()->with('error','Usuário não encontrado, verifica o username.');
        }        
        
        $Pendente = DB::table('escolas_estudantes')
        ->where('escolas_id',Auth::user()->escola->id)
        ->where('estudantes_id', $estudante->id)
        ->where('estado',2)->exists();

        if($Pendente){
            return redirect()->back()->with('error','O estudante ja tem um cadastro pendente dessa escola');
        }


        $EstaRota = DB::table('estudantes_rotas as t1')
        ->where('t1.rotas_id',$rotaId)
        ->where('t1.estudantes_id','=',$estudante->id)
        ->where('t1.estados',1)->exists(); 
        
        if($EstaRota){
            return redirect()->back()->with('error','O estudante ja faz parte dessa rota');
        }

        $JaEscolaRota = DB::table('estudantes_rotas as t1')
        ->join('rotas as t2','t2.id','=','t1.rotas_id')
        ->where('t1.estudantes_id','=',$estudante->id)
        ->where('t2.escolas_id',Auth::user()->escola->id)
        ->where('t1.estados',1)->exists();

        if($JaEscolaRota){
            return redirect()->back()->with('error','Estudante ja faz parte da instituição e possui uma outra rota');
        }

        $OutraEscolaRota = DB::table('estudantes_rotas as t1')
        ->where('t1.estudantes_id','=',$estudante->id)
        ->where('t1.estados',1)->exists();

        if($OutraEscolaRota){
            return redirect()->back()->with('error','Estudante pertence a uma rota de outra instituição');
        }

        $EssaEscola = DB::table('escolas_estudantes')
        ->where('estado',1)
        ->where('estudantes_id',$estudante->id)
        ->where('escolas_id',Auth::user()->escola->id)
        ->exists();

        if($EssaEscola){

            $RotaDesativa = estudantes_rotas::where('estudantes_id',$estudante->id)
            ->where('rotas_id',(int)$rotaId)
            ->where('estados',0)->first();

            if($RotaDesativa){
                $RotaDesativa->update(['estados' => 2]);
            }else{
                $novo = new estudantes_rotas();
                $novo->estudantes_id = $estudante->id;
                $novo->rotas_id = (int)$rotaId;
                $novo->estados = 2;
                $novo->save();
            }
            return redirect()->route('ListaEstudante')->with('sucess','Rota adicionada com sucesso, aguardando a confirmação do estudante.');
        }        
        
        $OutraEscola = DB::table('escolas_estudantes')
        ->where('estado',1)
        ->where('estudantes_id',$estudante->id)
        ->exists();

        if($OutraEscola){
            return redirect()->back()->with('error','Estudante pertence a outra instituição');
        }

        $EssaEscolaDesativa = DB::table('escolas_estudantes')
        ->where('estado', 0)
        ->where('estudantes_id',$estudante->id)
        ->where('escolas_id',Auth::user()->escola->id)
        ->first();

        if($EssaEscolaDesativa){
            DB::table('escolas_estudantes')
            ->where('id', $EssaEscolaDesativa->id)
            ->update(['estado' => 2]);

            $RotaDesativa = estudantes_rotas::where('estudantes_id',$estudante->id)
            ->where('rotas_id',(int)$rotaId)
            ->where('estados',0)->first();

            if($RotaDesativa){
                $RotaDesativa->update(['estados' => 2]);
            }else{
                $novo = new estudantes_rotas();
                $novo->estudantes_id = $estudante->id;
                $novo->rotas_id = (int)$rotaId;
                $novo->estados = 2;
                $novo->save();
            }

            return redirect()->route('ListaEstudante')->with('sucess','Estudante adicionado com sucesso, aguardando a confirmação do estudante.');
        }

        $novo = new escolas_estudantes();
        $novo->estudantes_id = $estudante->id;
        $novo->escolas_id = Auth::user()->escola->id;
        $novo->estado = 2;

        if($novo->save()){
            $rotaestudante = new estudantes_rotas();
            $rotaestudante->rotas_id = $rotaId;
            $rotaestudante ->estudantes_id = $estudante->id;
            $rotaestudante->estados = 2;
            $rotaestudante->save();

            return redirect()->route('ListaEstudante')->with('sucess','Estudante adicionado com sucesso, aguardando a confirmação do estudante.');
        }else{
            return redirect()->back()->with('error','Erro ao cadastrar o estudante');
        }

    }

    public function DesvincularMotorista($id){

        $user = Auth::user();
        $escola = $user->escola;

        $motorista = motorista::find($id);

        if(!$motorista){
            return redirect()->back()->with('error','Motorista não encontrado');
        }

        $ViagemAtiva = DB::table('viagems as t1')
        ->where('t1.motoristas_id',$motorista->id)
        ->whereIn('t1.estado',[1,2])->exists();

        if($ViagemAtiva){
            return redirect()->back()->with('error','Motorista está em viagem');
        }

        DB::beginTransaction();
        try{
            $conexao = escolas_motoristas::where('motoristas_id',$motorista->id)
            ->where('escolas_id',$escola->id)
            ->whereIn('estado',[1,2])->first();
    
            if(!$conexao){
                    DB::rollBack();
                return redirect()->back()->with('error','Parece não haver uma associação ativa com esse motorista');
            }
    
            $associacao = DB::table('motoristas_rotas_veiculos as t1')
            ->join('veiculos as t2','t2.id','=','t1.veiculos_id')
            ->where('t1.motoristas_id',$motorista->id)
            ->where('t2.escolas_id',$escola->id)
            ->where('t1.estado',1)->value('t1.id');
    
            if($associacao){
                 motoristas_rotas_veiculos::where('id',$associacao)->delete();
            }
    
            $conexao->update(['estado' => 0]);
            DB::commit();

        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error','Erro ao desvincular se do Motorista'.$e->getMessage());
        }
        
        return redirect()->route('ListaMotorista')->with('sucess','Motorista desvinculado com sucesso');
    }

    public function DesassociarMotorista($id){

        $user = Auth::user();
        $escola = $user->escola;

        $motorista = motorista::find($id);

        if(!$motorista){
            return redirect()->back()->with('error','Motorista não encontrado');
        }
      
        DB::beginTransaction();
        try {
            $Associacao = motoristas_rotas_veiculos::with('veiculo')
            ->where('motoristas_id', $motorista->id)
            ->where('estado', 1)
            ->whereHas('veiculo', function($query) use ($escola) {
                $query->where('escolas_id', $escola->id);
            })->first();

            if(!$Associacao){
                DB::rollBack();
                return redirect()->back()->with('error','Parece que o motorista não está associado a uma rota e veículo');
            }

            $Associacao->delete();

            $EscolaMotorista = escolas_motoristas::where('motoristas_id', $motorista->id)
            ->where('escolas_id', $escola->id)
            ->where('estado', 2)
            ->first();
            
            if(!$EscolaMotorista){
                DB::rollBack();
                return redirect()->back()->with('error','Erro ao desassociar o motorista');
            }

            $EscolaMotorista->update(['estado' => 1]);
            
            DB::commit();
            return redirect()->route('ListaMotorista')->with('sucess','Motorista desassociado com sucesso');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao desassociar o motorista: ' . $e->getMessage());
        }

    }
    

}
