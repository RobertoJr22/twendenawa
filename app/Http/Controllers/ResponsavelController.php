<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResponsavelRequest;
use app\Models;
use App\Models\estudantes_responsavels;
use App\Models\responsavel;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ResponsavelController extends Controller
{
    public function ResponsavelRegister(StoreResponsavelRequest $request){
        
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
            
            $responsavel = New responsavel(); 
            $responsavel->users_id = $user->id;
            $responsavel->foto = $imagePath;
            $responsavel->DataNascimento = $request->DataNascimento;
            $responsavel->BI = $request->BI;
            $responsavel->endereco = $request->endereco;
            $responsavel->telefone = $request->telefone;
            $responsavel->sexos_id = $request->sexos_id;
            $responsavel->save();

            DB::commit();
        }catch(\Exception $e ){
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao cadastrar: ' . $e->getMessage());
        }
        
        Auth::login($user);
        return redirect()->route('TelaResponsavel')->with('sucess','Bem vindo ao Twendenawa');

    }


    public function MainResponsavel(){
        
        $user = Auth::user();

        
        $responsavel = $user->responsavel;

        $estudantes = DB::table('estudantes_responsavels as t1')
        ->join('responsavels as t2','t2.id','=','t1.responsavels_id')
        ->join('estudantes as t3','t3.id','=','t1.estudantes_id')
        ->join('users as t4','t4.id','=','t3.users_id')
        ->where('t1.estado','=',1)
        ->where('t1.responsavels_id','=',$responsavel->id)
        ->select('t3.id as id','t4.name as nome')->get();

        $viagens = DB::table('dados_viagems as t1')
        ->join('estudantes as t2', 't1.estudantes_id', '=', 't2.id')
        ->join('estudantes_responsavels as t3','t3.estudantes_id','=','t2.id')
        ->join('viagems as t4','t4.id','=','t1.viagems_id')
        ->join('users as t5','t5.id','=','t2.users_id')
        ->join('responsavels as t6','t6.id','=','t3.responsavels_id')
        ->where('t3.responsavels_id','=',$responsavel->id)
        ->where('t4.estado','=',1)
        ->select('t2.id as IdEstudante','t5.name as Nome')->get();
    



        // Recupera as notificações do responsável, ordenando as mais recentes primeiro
        $notificacoes = $responsavel->notifications()->orderBy('created_at', 'desc')->get();
    
        return view('Responsavel.MainResponsavel', compact('user', 'responsavel', 'notificacoes', 'estudantes', 'viagens'));
    }

    public function DetalhesViagem($id){

        $user = Auth::user();
       
        $responsavel = $user->responsavel;

        $viagem = DB::table('DadosViagem as t1')
            ->join('estudantes as t2', 't1.estudantes_id', '=', 't2.id')
            ->join('motoristas as t3', 't1.motoristas_id', '=', 't3.id')
            ->join('users as t4', 't4.id', '=', 't3.users_id')
            ->join('users as t5', 't5.id', '=', 't2.users_id')
            ->join('estudantes_responsavels as t6', 't6.estudantes_id', '=', 't2.id')
            ->join('responsavels as t7', 't7.id', '=', 't6.responsavels_id')
            ->join('Motoristas_rotas_veiculos as t8', 't8.motoristas_id', '=', 't3.id')
            ->join('veiculos as t9', 't9.id', '=', 't8.veiculos_id')
            ->join('rotas as t10', 't10.id', '=', 't8.rotas_id')
            ->join('turnos as t11', 't11.id', '=', 't3.turnos_id')
            ->join('modelo as t12', 't12.id', '=', 't9.modelos_id')
            ->join('marcas as t13', 't13.id', '=', 't12.marcas_id')
            ->join('viagems as t14', 't14.id', '=', 't1.viagems_id')
            ->join('escolas as t15', 't15.id', '=', 't10.escolas_id')
            ->join('users as t16', 't16.id', '=', 't15.users_id')
            ->where('t7.id', '=', $responsavel->id)
            ->where('t14.estado', '=', 1)
            ->where('t2.id','=',$id)
            ->select(
            't14.id as NumeroDaViagem',
            't5.name as NomeEstudante',
            't4.name as NomeMotorista',
            't2.telefone as TelefoneEstudante',
            't3.telefone as TelefoneMotorista',
            't3.BI as BilheteMotorista',
            't9.Matricula as Matricula',
            't13.nome as Marca',
            't12.nome as Modelo',
            't11.nome as TurnoMotorista',
            't10.nome as rotaMotorista',
            't10.PontoA as PontoA',
            't10.PontoB as PontoB',
            't2.DataNascimento as DataNascimento',      // Para cálculo da idade
            't16.name as Instituicao',                   // Nome da instituição
            't7.telefone as TelefoneResponsavel',        // Contato do responsável
            't14.hora_inicio as HoraInicio',             // Hora de início da viagem
            't14.destino as Destino',                    // Destino da viagem
            't14.estado as Estado',                      // Estado da viagem (para exibir "Em Andamento")
            't9.placa as Placa',                         // Placa do veículo
            't9.capacidade as Capacidade',               // Capacidade do veículo
            't1.relatorio as Relatorio',                 // Relatório de eventualidades
            't1.created_at as RelatorioData'             // Data do relatório
        )
        ->first();

        return view('/Escola/DetalhesViagem',compact('viagem', 'user', 'responsavel'));
    }

    public function InfoEstudante($id)
    {
        $user = Auth::user();
        $responsavel = $user->responsavel;
    
        $estudantes = DB::table('estudantes as t1')
            ->join('users as t2', 't2.id', '=', 't1.users_id')
            ->join('estudantes_responsavels as t3', 't3.estudantes_id', '=', 't1.id')
            ->join('turnos as t4', 't4.id', '=', 't1.turnos_id')
            ->join('estudantes_rotas as t5', 't5.estudantes_id', '=', 't1.id')
            ->join('rotas as t6', 't6.id', '=', 't5.rotas_id')
            ->join('escolas as t7', 't7.id', '=', 't6.escolas_id')
            ->join('users as t8', 't8.id', '=', 't7.users_id')
            ->where('t3.estado', '=', 1)
            ->where('t3.responsavels_id', '=', $responsavel->id)
            ->select(
                't4.HoraRegresso as HoraRegresso',
                't4.HoraIda as HoraIda',
                't4.nome as Turno',
                't6.PontoB as PontoB',
                't6.PontoA as PontoA',
                't6.nome as NomeRota',
                't8.name as escola',
                't2.name as NomeEstudante',
                't1.DataNascimento as datanascimento',
                't1.telefone',
                't1.foto as foto',
                't1.id as id'
            )
            ->get();
    
        return view('Estudante.InfoEstudante', compact('estudantes'));
    }

    public function DesfazerConexao($id){

        $responsavel = Auth::user()->responsavel;

        $atualizar = DB::table('estudantes_responsavels')->where('estudantes_id','=',$id)
                                            ->where('responsavels_id','=',$responsavel->id)
                                            ->update(['estado'=> 0]);
        if($atualizar){
            return redirect()->route('TelaResponsavel')->with('sucess','Estudante removido da tua lista');
        }else{
            return redirect()->back()->with('error','Erro ao remover estudante da tua lista');
        }
    }

    public function ExibirConexao(Request $request){

        $responsaveis = collect();
        $estudantes = collect();
        $SearchResponsavel = null;
        $SearchEstudante = null;
        $estado = null;

        if($request->filled('search')){
            if($request->input('TipoUsuario') == 2){
                $SearchResponsavel = DB::table('responsavels as t1')->join('users as t2','t2.id','=','t1.users_id')
                                                            ->select(
                                                                't1.id as id',                                                                't1.telefone as telefone',
                                                                't2.name as nome',
                                                                't1.foto as foto'
                                                                    )
                                                            ->where('t1.id','=',$request->input('search'))
                                                            ->first();
                if($SearchResponsavel){
                    $estado = DB::table('estudantes_responsavels as t1')
                                ->where('responsavels_id','=', $SearchResponsavel->id)
                                ->select('t1.estado as estado')
                                ->first();
                }
            }elseif($request->input('TipoUsuario') == 4){
                $SearchEstudante = DB::table('estudantes as t1')
                                ->join('users as t2','t2.id','=','t1.users_id')
                                ->join('turnos as t3','t3.id','=','t1.turnos_id')
                                ->join('estudantes_rotas as t4','t4.estudantes_id','=','t1.id')
                                ->join('rotas as t5','t5.id','=','t4.rotas_id')
                                ->join('escolas as t6','t6.id','=','t5.escolas_id')
                                ->join('users as t7','t7.id','=','t6.users_id')
                                ->select(
                                    't1.id as id',
                                    't1.telefone as telefone',
                                    't2.name as nome',
                                    't7.name as instituicao',
                                    't1.foto as foto'
                                        )
                                ->where('t1.id','=',$request->input('search'))
                                ->first();
                if($SearchEstudante){
                    $estado = DB::table('estudantes_responsavels as t1')
                            ->where('estudantes_id','=', $SearchEstudante->id)
                            ->select('t1.estado as estado')
                            ->first();
                }
            }
        }
        if(Auth::user()->tipo_usuario_id == 4){
            $estudantes = DB::table('estudantes_responsavels as t1')
                            ->join('estudantes as t2','t2.id','=','t1.estudantes_id')
                            ->join('users as t3','t3.id','=','t2.users_id')
                            ->where('t1.estado','=',4)
                            ->where('t1.responsavels_id','=',Auth::user()->responsavel->id)
                            ->select(
                                't2.id as id',
                                't3.name as nome',
                                )
                            ->get();
        }elseif(Auth::user()->tipo_usuario_id == 2 ){
            $responsaveis = DB::table('estudantes_responsavels as t1')
                        ->join('responsavels as t2','t2.id','=','t1.responsavels_id')
                        ->join('users as t3','t3.id','=','t2.users_id')
                        ->where('t1.estado','=',2)
                        ->where('t1.estudantes_id','=',Auth::user()->estudante->id)
                        ->select(
                            't2.id as id',
                            't3.name as nome',
                            )
                        ->get();
        }

        return view('Responsavel.conexao', compact('estudantes','responsaveis','SearchEstudante','SearchResponsavel','estado'));
   }
   public function AcaoConexao($id,$acao,$tipousuario){
        switch($acao){
            /* 
                0 = pedir conexao
                1 = remover conexao
                2 = Aceitar conexao 
            */
            case 0:
                if($tipousuario == 4){
                    $afetado = DB::table('estudantes_responsavels')
                                ->where('responsavels_id','=',Auth::user()->responsavel->id)
                                ->where('estudantes_id','=',$id)
                                ->where('estado','=',0)
                                ->update(['estado'=>2]);
                    if($afetado > 0){
                        return redirect()->back()->with('sucess','Pedido enviado com sucesso');
                    }else{
                        return redirect()->back()->with('error','Erro ao efectuar o pedido de conexão');
                    }
                }elseif($tipousuario == 2){
                    $afetado = DB::table('estudantes_responsavels')
                                ->where('responsavels_id','=',$id)
                                ->where('estudantes_id','=',Auth::user()->estudante->id)
                                ->where('estado','=',0)
                                ->update(['estado'=>4]);
                    if($afetado > 0){
                        return redirect()->back()->with('sucess','Pedido enviado com sucesso');
                    }else{
                        return redirect()->back()->with('error','Erro ao efectuar o pedido de conexão');
                    }
                }
                break;
            case 1:
                if($tipousuario == 4){
                    $afetado = DB::table('estudantes_responsavels')
                                ->where('responsavels_id','=',Auth::user()->responsavel->id)
                                ->where('estudantes_id','=',$id)
                                ->where('estado','=',1)
                                ->update(['estado'=>0]);
                    if($afetado > 0){
                        return redirect()->back()->with('sucess','Conexão desfeita com sucesso');
                    }else{
                        return redirect()->back()->with('error','Erro ao desfazer a conexão');
                    }
                }elseif($tipousuario == 2){
                    $afetado = DB::table('estudantes_responsavels')
                                ->where('responsavels_id','=',$id)
                                ->where('estudantes_id','=',Auth::user()->estudante->id)
                                ->where('estado','=',1)
                                ->update(['estado'=>0]);
                    if($afetado > 0){
                        return redirect()->back()->with('sucess','Conexão desfeita com sucesso');
                    }else{
                        return redirect()->back()->with('error','Erro ao desfazer a conexão');
                    }
                }
                break;
            case 2:
                if($tipousuario == 4){
                    $afetado = DB::table('estudantes_responsavels')
                                ->where('responsavels_id','=',Auth::user()->responsavel->id)
                                ->where('estudantes_id','=',$id)
                                ->where('estado','=',4)
                                ->update(['estado'=>1]);
                    if($afetado > 0){
                        return redirect()->back()->with('sucess','Pedido de conexão aceite');
                    }else{
                        return redirect()->back()->with('error','Erro ao aceitar o pedido de conexão');
                    }
                }elseif($tipousuario == 2){
                    $afetado = DB::table('estudantes_responsavels')
                                ->where('responsavels_id','=',$id)
                                ->where('estado','=',2)
                                ->where('estudantes_id','=',Auth::user()->estudante->id)
                                ->update(['estado'=>1]);
                    if($afetado > 0){
                        return redirect()->back()->with('sucess','Pedido de conexão aceite');
                    }else{
                        return redirect()->back()->with('error','Erro ao aceitar o pedido de conexão');
                    }
                }
                break;
            case 3:
                if($tipousuario == 4){
                    $afetado = DB::table('estudantes_responsavels')
                                ->where('responsavels_id','=',Auth::user()->responsavel->id)
                                ->where('estudantes_id','=',$id)
                                ->where('estado','=',4)
                                ->update(['estado'=>0]);
                    if($afetado > 0){
                        return redirect()->back()->with('sucess','Pedido de conexão negado');
                    }else{
                        return redirect()->back()->with('error','Erro ao negar pedido de conexão');
                    }
                }elseif($tipousuario == 2){
                    $afetado = DB::table('estudantes_responsavels')
                                ->where('responsavels_id','=',$id)
                                ->where('estado','=',2)
                                ->where('estudantes_id','=',Auth::user()->estudante->id)
                                ->update(['estado'=>0]);
                    if($afetado > 0){
                        return redirect()->back()->with('sucess','Pedido de conexão negado');
                    }else{
                        return redirect()->back()->with('error','Erro ao negar pedido de conexão');
                    }
                }
                break;
            case 4:
                if($tipousuario == 4){
                    $afetado = DB::table('estudantes_responsavels')
                                ->where('responsavels_id','=',Auth::user()->responsavel->id)
                                ->where('estudantes_id','=',$id)
                                ->where('estado','=',2)
                                ->update(['estado'=>0]);
                    if($afetado > 0){
                        return redirect()->back()->with('sucess','Pedido de conexão cancelado');
                    }else{
                        return redirect()->back()->with('error','Erro ao cancelar pedido de conexão');
                    }
                }elseif($tipousuario == 2){
                    $afetado = DB::table('estudantes_responsavels')
                                ->where('responsavels_id','=',$id)
                                ->where('estado','=',4)
                                ->where('estudantes_id','=',Auth::user()->estudante->id)
                                ->update(['estado'=>1]);
                    if($afetado > 0){
                        return redirect()->back()->with('sucess','Pedido de conexão cancelado');
                    }else{
                        return redirect()->back()->with('error','Erro ao cancelar pedido de conexão');
                    }
                }
                break;
            default:
                return redirect()->back()->with('error', 'Ação inválida.');
        }
   }
    
}