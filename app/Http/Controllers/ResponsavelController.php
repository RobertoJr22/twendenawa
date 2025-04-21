<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StoreResponsavelRequest;
use app\Models;
use App\Models\escolas_motoristas;
use App\Models\estudante;
use App\Models\estudantes_responsavels;
use App\Models\responsavel;
use App\Models\User;
use App\Notifications\EstudanteEventNotification;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Notifications\StudentEventNotification;


class ResponsavelController extends Controller
{
    public function ResponsavelRegister(StoreResponsavelRequest $request)
    {
        DB::beginTransaction();
        try {
            $imagePath = null;
            if ($request->hasFile('foto')) {
                $imagePath = $request->file('foto')->store('avatares', 'public');
            }
            
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password); 
            $user->tipo_usuario_id = $request->tipo_usuario_id;
            
            // Gera o username automaticamente baseado no nome do usuário
            $user->username = User::generateUniqueUsername($user->name);
            
            $user->save();
            
            $responsavel = new Responsavel(); 
            $responsavel->users_id = $user->id;
            $responsavel->foto = $imagePath;
            $responsavel->DataNascimento = $request->DataNascimento;
            $responsavel->BI = $request->BI;
            $responsavel->endereco = $request->endereco;
            $responsavel->telefone = $request->telefone;
            $responsavel->sexos_id = $request->sexos_id;
            $responsavel->save();

            DB::commit();
        } catch (\Exception $e) {
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
        ->whereIn('t4.estado',[1,2])
        ->select('t2.id as IdEstudante','t5.name as NomeEstudante')->get();
    



        // Recupera as notificações do responsável, ordenando as mais recentes primeiro
        $notificacoes = $responsavel->notifications()->orderBy('created_at', 'desc')->get();
    
        return view('Responsavel.MainResponsavel', compact('user', 'responsavel', 'notificacoes', 'estudantes', 'viagens'));
    }

    public function DetalhesViagem($id){

        $user = Auth::user();
       
        $responsavel = $user->responsavel;
        $relatorios = collect();

        $viagem = DB::table('viagems as t1')
        ->leftJoin('dados_viagems as t2','t2.viagems_id','=','t1.id')
        ->join('estudantes as t3','t2.estudantes_id','=','t3.id')
        ->join('users as t4','t4.id','=','t3.users_id')  //para estudantes
        ->join('estudantes_rotas as t5','t5.estudantes_id','=','t3.id')
        ->join('rotas as t6','t6.id','=','t5.rotas_id')
        ->join('escolas as t7','t7.id','=','t6.escolas_id')
        ->join('turnos as t8','t8.id','=','t3.turnos_id')
        ->join('motoristas as t9','t9.id','=','t1.motoristas_id')
        ->join('users as t10','t10.id','=','t9.users_id') // users para motorista
        ->join('motoristas_rotas_veiculos as t11','t11.motoristas_id','=','t9.id')
        ->join('veiculos as t12','t12.id','=','t11.veiculos_id')
        ->join('modelos as t13','t13.id','=','t12.modelos_id')
        ->join('marcas as t14','t14.id','=','t13.marcas_id')
        ->join('users as t15','t15.id','=','t7.users_id')  //para  escolas
        ->select(
            't1.id',
            't1.motoristas_id',
            't4.name as Estudante',
            't3.DataNascimento',
            't15.name as Escola',
            't7.telefone as TelEscola',
            't10.name as Motorista',
            't9.telefone as TelMotorista',
            't1.updated_at as HoraInicio',
            't6.nome as rota',
            't6.PontoA',
            't6.PontoB',
            't8.HoraIda',
            't8.HoraRegresso',
            't14.nome as marca',
            't13.nome as modelo',
            't12.Matricula',
            't12.capacidade',
            't8.nome as Turno'
        )
        ->whereIn('t1.estado',[1,2])
        ->where('t11.estado', 1)
        ->where('t3.id',$id)
        ->first();

        if($viagem && $viagem->id){
            $relatorios = DB::table('dados_viagems as t1')
                            ->join('viagems as t2','t2.id','=','t1.viagems_id')
                            ->select(
                                't1.relatorio',
                                't1.created_at'
                            )
                            ->where('t2.id',$viagem->id)
                            ->whereNotNull('t1.relatorio')
                            ->get();
        }

        return view('Estudante.DetalhesViagem',compact('viagem', 'user', 'responsavel', 'relatorios'));
    }

    public function InfoEstudante($id)
    {
        $user = Auth::user();
        $responsavel = $user->responsavel;
    
        $estudantes = DB::table('estudantes as t1')
        ->join('users as t2', 't2.id', '=', 't1.users_id')
        ->join('estudantes_responsavels as t3', 't3.estudantes_id', '=', 't1.id')
        ->join('turnos as t4', 't4.id', '=', 't1.turnos_id')
        ->leftJoin('estudantes_rotas as t5', 't5.estudantes_id', '=', 't1.id')
        ->leftJoin('rotas as t6', 't6.id', '=', 't5.rotas_id')
        ->leftJoin('escolas as t7', 't7.id', '=', 't6.escolas_id')
        ->leftJoin('users as t8', 't8.id', '=', 't7.users_id')
        ->where('t3.estado', '=', 1)
        ->where('t3.responsavels_id', '=', $responsavel->id)
        ->where('t1.id', $id)
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
        ->first();

        $responsaveis = DB::table('estudantes_responsavels as t1')
        ->join('responsavels as t2','t2.id','=','t1.responsavels_id')
        ->join('users as t3','t3.id','=','t2.users_id')
        ->select(
            't2.id',
            't3.name as nome'
        )
        ->where('t1.estado','=',1)
        ->where('t1.estudantes_id',$id)
        ->whereNot('t1.responsavels_id',$responsavel->id)
        ->get();
    
    
        return view('Estudante.InfoEstudante', compact('estudantes','responsaveis'));
    }

    public function DesfazerConexao($id){
        
        if(Auth::check() && Auth::user()->tipo_usuario_id == 4){
            $responsavel = Auth::user()->responsavel;

            $atualizar = DB::table('estudantes_responsavels')->where('estudantes_id','=',$id)
                                                ->where('responsavels_id','=',$responsavel->id)
                                                ->where('estado', 1)
                                                ->update(['estado'=> 0]);
            if($atualizar){
                return redirect()->route('TelaResponsavel')->with('sucess','Estudante removido da tua lista');
            }else{
                return redirect()->back()->with('error','Erro ao remover estudante da tua lista');
            }
        }elseif(Auth::check() && Auth::user()->tipo_usuario_id == 2){
            $estudante = Auth::user()->estudante;

            $atualizar = DB::table('estudantes_responsavels')->where('estudantes_id',$estudante->id)
                                                ->where('responsavels_id',$id)
                                                ->where('estado', 1)
                                                ->update(['estado'=> 0]);
            if($atualizar){
                return redirect()->route('TelaEstudante')->with('sucess','Responsável removido da tua lista');
            }else{
                return redirect()->back()->with('error','Erro ao remover estudante da tua lista');
            }
        }
    }

    public function ExibirConexao(Request $request,$id = null){

        $responsaveis = collect();
        $estudantes = collect();
        $Escolas = collect();
        $SearchResponsavel = null;
        $SearchEstudante = null;
        $SearchEscola = null; 
        $estado = null;

        
        
        if($request->filled('search')){
            $id = null;
            if($request->input('TipoUsuario') == 2){
                $EstudanteId = Auth::user()->estudante->id;
                $SearchResponsavel = DB::table('responsavels as t1')->join('users as t2','t2.id','=','t1.users_id')
                                                            ->select(
                                                                't1.id as id',                                                                't1.telefone as telefone',
                                                                't2.name as nome',
                                                                't1.foto as foto'
                                                                    )
                                                            ->where('t2.username','=',$request->input('search'))
                                                            ->first();
                if($SearchResponsavel){
                    $estado = DB::table('estudantes_responsavels as t1')
                                ->where('responsavels_id', $SearchResponsavel->id)
                                ->where('estudantes_id', $EstudanteId)
                                ->select('t1.estado')
                                ->first();
                }
            }elseif($request->input('TipoUsuario') == 4){
                $ResponsavelId = Auth::user()->responsavel->id;
                $SearchEstudante = DB::table('estudantes as t1')
                                ->join('users as t2','t2.id','=','t1.users_id')
                                ->select(
                                    't1.id as id',
                                    't1.telefone as telefone',
                                    't2.name as nome',
                                    't1.foto as foto'
                                        )
                                ->where('t2.username',$request->input('search'))
                                ->first();
                if($SearchEstudante){
                    $estado = DB::table('estudantes_responsavels as t1')
                            ->where('estudantes_id', $SearchEstudante->id)
                            ->where('t1.responsavels_id',$ResponsavelId)
                            ->select('t1.estado')
                            ->first();
                }
            }
        }

            // Se for um clique em um pedido de conexão, buscar os dados do usuário correspondente
        if ($id) {
            if (Auth::user()->tipo_usuario_id == 4) {
                $ResponsavelId = Auth::user()->responsavel->id;
                $SearchEstudante = DB::table('estudantes as t1')
                    ->join('users as t2', 't2.id', '=', 't1.users_id')
                    ->select('t1.id', 't1.telefone', 't2.name as nome', 't1.foto')
                    ->where('t1.id', $id)
                    ->first();
                if($SearchEstudante){
                    $estado = DB::table('estudantes_responsavels as t1')
                            ->where('estudantes_id', $SearchEstudante->id)
                            ->where('t1.responsavels_id',$ResponsavelId)
                            ->select('t1.estado as estado')
                            ->first();
                }
            } elseif (Auth::user()->tipo_usuario_id == 2) {
                $EstudanteId = Auth::user()->estudante->id;
                $SearchResponsavel = DB::table('responsavels as t1')
                    ->join('users as t2', 't2.id', '=', 't1.users_id')
                    ->select('t1.id', 't1.telefone', 't2.name as nome', 't1.foto')
                    ->where('t1.id', $id)
                    ->first();
                if($SearchResponsavel){
                    $estado = DB::table('estudantes_responsavels as t1')
                                ->where('responsavels_id', $SearchResponsavel->id)
                                ->where('estudantes_id', $EstudanteId)
                                ->select('t1.estado')
                                ->first();
                }
            }elseif (Auth::user()->tipo_usuario_id == 3) {
                $MotoristaId = Auth::user()->motorista->id;
                $SearchEscola = DB::table('escolas as t1')
                    ->join('users as t2', 't2.id', '=', 't1.users_id')
                    ->join('bairros as t3','t3.id','=','t1.bairros_id')
                    ->join('distritos as t4','t4.id','=','t3.distritos_id')
                    ->join('municipios as t5','t5.id','=','t4.municipios_id')
                    ->select('t1.id',
                             't1.telefone',
                             't2.name as nome',
                             't1.foto',
                             't5.nome as municipio',
                             't4.nome as distrito',
                             't3.nome as bairro'
                             )
                    ->where('t1.id', $id)
                    ->first();
                if($SearchEscola){
                    $estado = DB::table('escolas_motoristas as t1')
                            ->where('escolas_id', $SearchEscola->id)
                            ->where('t1.Motoristas_id',$MotoristaId)
                            ->select('t1.estado')
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
        }elseif(Auth::user()->tipo_usuario_id == 3 ){
            $MotoristaId = Auth::user()->motorista->id;

            $Escolas = DB::table('escolas_motoristas as t0')
                ->join('escolas as t1', 't1.id','=','t0.escolas_id')
                ->join('users as t2', 't2.id', '=', 't1.users_id')
                ->select(
                            't1.id',
                            't2.name as nome',
                         )
                ->where('t0.motoristas_id', $MotoristaId)
                ->where('t0.estado',3)
                ->get();
        }

        return view('Responsavel.conexao', compact('estudantes','responsaveis','SearchEstudante','SearchResponsavel','Escolas','SearchEscola','estado'));
   }
   
    public function AcaoConexao($id,$acao,$tipousuario){
        switch($acao){
            /* 
                0 = enviar pedido
                1 = desfazer pedido
                2 = Aceitar pedido
                3 = Negar pedido
                4 = Cancelar pedido
            */
            case 0:
                if($tipousuario == 4){
                    $buscar = db::table('estudantes_responsavels')
                    ->where('estado','=',0)
                    ->where('estudantes_id','=',$id)
                    ->where('responsavels_id','=',Auth::user()->responsavel->id)
                    ->first();

                    if($buscar){
                        $afetado = DB::table('estudantes_responsavels')
                        ->where('responsavels_id','=',Auth::user()->responsavel->id)
                        ->where('estudantes_id','=',$id)
                        ->where('estado','=',0)
                        ->update(['estado'=>2]);
                        if($afetado > 0){  
                            EstudanteEventNotification::NotifyEstudante('pedido_conexao',$id,Auth::user()->responsavel->id);                      
                            return redirect()->back()->with('sucess','Pedido enviado com sucesso');
                        }else{
                            return redirect()->back()->with('error','Erro ao efectuar o pedido de conexão');
                        }
                    }else{
                        $novoPedido = new estudantes_responsavels();
                        $novoPedido->estudantes_id = $id;
                        $novoPedido->responsavels_id = Auth::user()->responsavel->id;
                        $novoPedido->estado = 2;
                        $novoPedido->save();
                        if($novoPedido->save()){
                            EstudanteEventNotification::NotifyEstudante('pedido_conexao',$id,Auth::user()->responsavel->id);
                            return redirect()->back()->with('sucess','Pedido enviado com sucesso');
                        }else{
                            return redirect()->back()->with('error','Erro ao efectuar o pedido de conexão');
                        }
                    }
                }elseif($tipousuario == 2){
                    $buscar = db::table('estudantes_responsavels')
                    ->where('estado','=',0)
                    ->where('responsavels_id','=',$id)
                    ->where('estudantes_id','=',Auth::user()->estudante->id)
                    ->first();

                    if($buscar){
                        $afetado = DB::table('estudantes_responsavels')
                        ->where('responsavels_id','=',$id)
                        ->where('estudantes_id','=',Auth::user()->estudante->id)
                        ->where('estado','=',0)
                        ->update(['estado'=>4]);
                        if($afetado > 0){   
                            StudentEventNotification::notifyResponsaveis('pedido_conexao', Auth::user()->estudante, $id);                     
                            return redirect()->back()->with('sucess','Pedido enviado com sucesso');
                        }else{
                            return redirect()->back()->with('error','Erro ao efectuar o pedido de conexão');
                        }
                    }else{
                        $novoPedido = new estudantes_responsavels();
                        $novoPedido->estudantes_id = Auth::user()->estudante->id;
                        $novoPedido->responsavels_id = $id;
                        $novoPedido->estado = 4;
                        $novoPedido->save();
                        if($novoPedido->save()){
                            StudentEventNotification::notifyResponsaveis('pedido_conexao', Auth::user()->estudante, $id);
                            return redirect()->back()->with('sucess','Pedido enviado com sucesso');
                        }else{
                            return redirect()->back()->with('error','Erro ao efectuar o pedido de conexão');
                        }
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
                        EstudanteEventNotification::NotifyEstudante('remocao_conexao',$id,Auth::user()->responsavel->id);
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
                        StudentEventNotification::notifyResponsaveis('remocao_conexao',Auth::user()->estudante,$id);
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
                        EstudanteEventNotification::NotifyEstudante('aceite_conexao',$id,Auth::user()->responsavel->id);
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
                        StudentEventNotification::notifyResponsaveis('aceite_conexao',Auth::user()->estudante,$id);
                        return redirect()->back()->with('sucess','Pedido de conexão aceite');
                    }else{
                        return redirect()->back()->with('error','Erro ao aceitar o pedido de conexão');
                    }
                }elseif($tipousuario == 3){
                    DB::beginTransaction();
                    try{
                        $escola = DB::table('escolas_motoristas')
                                ->where('escolas_id','=',$id)
                                ->where('estado','=',3)
                                ->where('motoristas_id','=',Auth::user()->motorista->id)
                                ->update(['estado'=>1]);
                        if($escola > 0){
                            escolas_motoristas::where('motoristas_id',Auth::user()->motorista->id)
                            ->where('estado',3)->delete();
                            DB::commit();
                            return redirect()->back()->with('sucess','Pedido de conexão aceite');
                        }else{
                            DB::rollBack();
                            return redirect()->back()->with('error','Erro ao aceitar o pedido de conexão');
                        }
                    }catch(Exception $e){
                        DB::rollBack();
                        return redirect()->back()->with('error','Erro ao aceitar o pedido de conexão'.$e->getMessage());
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
                        EstudanteEventNotification::NotifyEstudante('negacao_conexao',$id,Auth::user()->responsavel->id);
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
                        StudentEventNotification::notifyResponsaveis('negacao_conexao',Auth::user()->estudante,$id);
                        return redirect()->back()->with('sucess','Pedido de conexão negado');
                    }else{
                        return redirect()->back()->with('error','Erro ao negar pedido de conexão');
                    }
                }elseif($tipousuario == 3){
                    $afetado = DB::table('escolas_motoristas')
                                ->where('escolas_id','=',$id)
                                ->where('estado','=',3)
                                ->where('motoristas_id','=',Auth::user()->motorista->id)
                                ->update(['estado'=>0]);
                    if($afetado > 0){
                        return redirect()->back()->with('sucess','Pedido de conexão aceite');
                    }else{
                        return redirect()->back()->with('error','Erro ao aceitar o pedido de conexão');
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
                                ->update(['estado'=>0]);
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