<?php

namespace App\Http\Controllers;



use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\estudante;
use App\Models\User;
use App\Models\responsavel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreStudentRequest;
use App\Models\DadosViagem;
use App\Models\estudantes_rotas;
use App\Models\motoristas_rotas_veiculos;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\StudentEventNotification;
use App\Notifications\EstudanteEventNotification;


use function Laravel\Prompts\select;

class EstudanteController extends Controller
{
    public function index(){
        return view('Estudante.CadastrarEstudante');
    }

    public function MainEstudante() {
        $user = Auth::user();
        $estudante = $user->estudante;
        $turno = $estudante->turno;
    
        $rota = DB::table('rotas as t1')
            ->join('estudantes_rotas as t2','t2.rotas_id','=','t1.id')
            ->where('t2.estudantes_id','=',$estudante->id)
            ->where('t2.estados','=',1)
            ->select('t1.nome as nome','t1.PontoA as PontoA', 't1.PontoB as PontoB')
            ->first();
    
        $responsaveis = DB::table('Responsavels as t1')
            ->join('users as t2', 't2.id', '=', 't1.users_id')
            ->join('estudantes_responsavels as t3', 't3.responsavels_id', '=', 't1.id')
            ->where('t3.estudantes_id', '=', $estudante->id)
            ->where('t3.estado', '=', 1)
            ->select('t2.name as nome','t1.id')
            ->get();
    
        $escola = DB::table('escolas_estudantes as t0')
            ->join('escolas as t1','t1.id','=','t0.escolas_id')
            ->join('users as t2', 't2.id', '=', 't1.users_id')
            ->join('bairros as t3','t3.id','=','t1.bairros_id')
            ->join('distritos as t4','t4.id','=','t3.distritos_id')
            ->join('municipios as t5','t5.id','=','t4.municipios_id')
            ->select(
                't1.id',
                't1.telefone',
                't2.name as nome',
                't1.foto',
                't2.email',
                't5.nome as municipio',
                't4.nome as distrito',
                't3.nome as bairro'
            )
            ->where('t0.estado',1)
            ->where('t0.estudantes_id',$estudante->id)
            ->first();
    
        $escolas = DB::table('escolas_estudantes as t1')
            ->join('escolas as t2','t2.id','=','t1.escolas_id')
            ->join('users as t3','t3.id','=','t2.users_id')
            ->select(
                't2.id',
                't3.name as nome'
            )
            ->where('t1.estudantes_id',$estudante->id)
            ->where('t1.estado',2)
            ->get();
    
        $viagem = DB::table('viagems as t1')
            ->join('dados_viagems as t2', 't1.id', '=', 't2.viagems_id')
            ->join('motoristas as t3', 't1.motoristas_id', '=', 't3.id')
            ->join('users as t4', 't4.id', '=', 't3.users_id')
            ->join('turnos as t5', 't5.id', '=', 't3.turnos_id')
            ->join('motoristas_rotas_veiculos as t6', function($join) {
                $join->on('t6.motoristas_id', '=', 't3.id')
                     ->where('t6.estado', '=', 1);
            })
            ->join('veiculos as t7', 't7.id', '=', 't6.veiculos_id')
            ->join('modelos as t8', 't8.id', '=', 't7.modelos_id')
            ->join('marcas as t9', 't9.id', '=', 't8.marcas_id')
            ->join('rotas as t10', 't10.id', '=', 't6.rotas_id')
            ->select(
                't1.motoristas_id', // <--- adicionado aqui
                't8.nome as modelo',
                't9.nome as marca',
                't7.Matricula',
                't4.name as motorista',
                't10.PontoA',
                't10.PontoB',
                't5.HoraIda',
                't5.HoraRegresso',
                't1.estado'
            )
            ->whereIn('t1.estado',[1,2])
            ->where('t2.estudantes_id', $estudante->id)
            ->first();
    
        $notificacoes = $estudante->notifications()->orderBy('created_at', 'desc')->get();


    
        return view('Estudante.MainEstudante', compact(
            'escolas','estudante','turno','rota', 'user', 'escola','responsaveis', 'notificacoes','viagem'
        ) + [
            'motoristaId' => $viagem?->motoristas_id
        ]);
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

            // Gera o username automaticamente baseado no nome do usuário
            $user->username = User::generateUniqueUsername($user->name);

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

    public function SelecaoEstudante(Request $request) {

        $motorista = Auth::user()->motorista;
        $estudante = null;
        $Info = "Pesquisa pelo nome de utilizador do estudante.";
        $abordo = false;
    
        if ($request->filled('search')) {
            $estudante = DB::table('estudantes as t1')
                ->join('users as t2', 't2.id', '=', 't1.users_id')
                ->where('t2.estado', 1)
                ->where('t1.estado', 1)
                ->where('t2.username', $request->input('search')) // Adicionado filtro de pesquisa
                ->select(
                    't1.id',
                    't2.name as nome',
                    't1.telefone',
                    't2.email',
                    't1.foto'
                )->first();
            
            if (!$estudante) {
                $Info = "Nenhum estudante encontrado com este identificador.";
            } else {
                // Só verifica se o estudante está a bordo se ele existir
                $abordo = DB::table('dados_viagems as t1')
                    ->join('viagems as t2', 't2.id', '=', 't1.viagems_id')
                    ->where('t1.estudantes_id', $estudante->id)
                    ->where('t2.motoristas_id', $motorista->id)
                    ->where('t2.estado', 1)
                    ->exists();
            }
        }
    
        return view('Estudante.SelecaoEstudante', compact('estudante', 'Info', 'abordo'));
    }
    
    

    public function PagamentosEstudante(){
        return view('Estudante.PagamentosEstudante');
    }

    public function adicionarAbordo($id)
    {
        
        $motorista = Auth::user()->motorista;
        $hora = now()->format('H');
        $horaViagem = DB::table('turnos as t1')
            ->join('motoristas as t2', 't2.turnos_id', '=', 't1.id')
            ->where('t2.id', $motorista->id)
            ->select('t1.HoraIda','t1.HoraRegresso')
            ->first();

        $HoraIda = Carbon::parse($horaViagem->HoraIda)->format('H');
        $HoraRegresso = Carbon::parse($horaViagem->HoraRegresso)->format('H');

        $estudanteId = $id;

        if($hora >= $HoraIda && $hora <= $HoraRegresso){

            if($hora == $HoraIda){

                // Obter dados do motorista
                $DadosMotorista = DB::table('users as t1')
                ->join('escolas as t2', 't1.id', '=', 't2.users_id')
                ->join('veiculos as t3', 't2.id', '=', 't3.escolas_id')
                ->join('motoristas_rotas_veiculos as t4', 't3.id', '=', 't4.veiculos_id')
                ->join('motoristas as t5', 't5.id', '=', 't4.motoristas_id')
                ->join('turnos as t6', 't6.id', '=', 't5.turnos_id')
                ->join('rotas as t7', 't7.id', '=', 't4.rotas_id')
                ->where('t4.motoristas_id', $motorista->id)
                ->select('t1.name as escola', 't7.nome as rota', 't6.nome as turno', 't3.capacidade')
                ->first();

                if (!$DadosMotorista) {
                    return redirect()->back()->with('error', 'Não estás associado a uma instituição.');
                }

                // Verificar estudante
                $estudante = DB::table('estudantes as t1')
                    ->join('users as t2', 't2.id', '=', 't1.users_id')
                    ->where('t1.id', $estudanteId)
                    ->where('t2.estado', 1)
                    ->first();
                if (!$estudante) {
                    return redirect()->back()->with('error', 'Erro de sistema, não foi possível localizar o estudante.');
                }

                // Verificar se o estudante tem uma instituição ativa
                $VerifEscola = estudantes_rotas::where('estudantes_id', $estudanteId)
                ->where('estados', 1)
                ->exists();
                if (!$VerifEscola) {
                    return redirect()->back()->with('error', 'Estudante sem rota ou sem instituição.');
                }

                // Obter dados do estudante
                $DadosEstudante = DB::table('users as t1')
                ->join('escolas as t2', 't1.id', '=', 't2.users_id')
                ->join('rotas as t3', 't2.id', '=', 't3.escolas_id')
                ->join('estudantes_rotas as t4', 't3.id', '=', 't4.rotas_id')
                ->join('estudantes as t5', 't5.id', '=', 't4.estudantes_id')
                ->join('turnos as t6', 't6.id', '=', 't5.turnos_id')
                ->where('t4.estudantes_id', $estudanteId)
                ->select('t1.name as escola', 't3.nome as rota', 't6.nome as turno')
                ->first();

                // Verificar correspondência entre motorista e estudante
                if ($DadosEstudante->escola !== $DadosMotorista->escola) {
                    return redirect()->back()->with('error', 'Estudante pertence a outra Instituição.');
                }
            
                if ($DadosEstudante->turno !== $DadosMotorista->turno) {
                    return redirect()->back()->with('error', 'Estudante pertence a outro turno.');
                }
            
                if ($DadosEstudante->rota !== $DadosMotorista->rota) {
                    return redirect()->back()->with('error', 'Estudante pertence a outra rota.');
                }

                // Verificar se já existe uma viagem em andamento
                $ViagemAndamento = DB::table('viagems')
                ->where('motoristas_id', $motorista->id)
                ->where('estado', 2)
                ->exists();

                if ($ViagemAndamento) {
                    return redirect()->back()->with('error', 'O motorista tem uma viagem em andamento.');
                }

                // Buscar ou criar a viagem ativa
                $ViagemAtiva = DB::table('viagems')
                ->where('motoristas_id', $motorista->id)
                ->where('estado', 1)
                ->select('id')
                ->first();

                if (!$ViagemAtiva) {
                    $ViagemAtiva = DB::table('viagems')->insertGetId([
                        'motoristas_id' => $motorista->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $ViagemAtiva = $ViagemAtiva->id;
                }

                // Verificar se o estudante já está a bordo
                $Abordo = DB::table('dados_viagems')
                    ->where('estudantes_id', $id)
                    ->where('viagems_id', $ViagemAtiva)
                    ->exists();

                if ($Abordo) {
                    return redirect()->back()->with('error', 'Estudante já foi adicionado.');
                }

                // Verificar capacidade do veículo
                $capacidadeAtual = DB::table('dados_viagems')
                    ->where('viagems_id', $ViagemAtiva)
                    ->whereNotNull('estudantes_id')
                    ->count();

                if ($capacidadeAtual >= $DadosMotorista->capacidade) {
                    return redirect()->back()->with('error', 'Veículo lotado.');
                }

                // Adicionar estudante à viagem
                $resultado = DB::table('dados_viagems')->insert([
                    'estudantes_id' => $id,
                    'viagems_id' => $ViagemAtiva,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($resultado) {
                    return redirect()->route('TelaMotorista')->with('sucess', 'Estudante adicionado a bordo.');
                }

                return redirect()->back()->with('error', 'Erro ao adicionar estudante.');

            }elseif($hora == $HoraRegresso){

                $ViagemIda = DB::table('viagems')
                    ->where('motoristas_id', $motorista->id)
                    ->where('estado', 3)
                    ->exists();
                if(!$ViagemIda){
                    return redirect()->back()->with('error', 'Viagem de ida não iniciada, considerado uma falta');
                }else{
                    $ViagemIda = DB::table('viagems')
                    ->where('motoristas_id', $motorista->id)
                    ->where('estado', 3)
                    ->update(['estado' => 0]);

                    // Obter dados do motorista
                    $DadosMotorista = DB::table('users as t1')
                    ->join('escolas as t2', 't1.id', '=', 't2.users_id')
                    ->join('veiculos as t3', 't2.id', '=', 't3.escolas_id')
                    ->join('motoristas_rotas_veiculos as t4', 't3.id', '=', 't4.veiculos_id')
                    ->join('motoristas as t5', 't5.id', '=', 't4.motoristas_id')
                    ->join('turnos as t6', 't6.id', '=', 't5.turnos_id')
                    ->join('rotas as t7', 't7.id', '=', 't4.rotas_id')
                    ->where('t4.motoristas_id', $motorista->id)
                    ->select('t1.name as escola', 't7.nome as rota', 't6.nome as turno', 't3.capacidade')
                    ->first();

                    if (!$DadosMotorista) {
                        return redirect()->back()->with('error', 'Não estás associado a uma instituição.');
                    }

                    // Verificar estudante
                    $estudante = DB::table('estudantes as t1')
                        ->join('users as t2', 't2.id', '=', 't1.users_id')
                        ->where('t1.id', $estudanteId)
                        ->where('t2.estado', 1)
                        ->first();
                    if (!$estudante) {
                        return redirect()->back()->with('error', 'Erro de sistema, não foi possível localizar o estudante.');
                    }

                    // Verificar se o estudante tem uma instituição ativa
                    $VerifEscola = estudantes_rotas::where('estudantes_id', $estudanteId)
                    ->where('estados', 1)
                    ->exists();
                    if (!$VerifEscola) {
                        return redirect()->back()->with('error', 'Estudante sem rota ou sem instituição.');
                    }

                    // Obter dados do estudante
                    $DadosEstudante = DB::table('users as t1')
                    ->join('escolas as t2', 't1.id', '=', 't2.users_id')
                    ->join('rotas as t3', 't2.id', '=', 't3.escolas_id')
                    ->join('estudantes_rotas as t4', 't3.id', '=', 't4.rotas_id')
                    ->join('estudantes as t5', 't5.id', '=', 't4.estudantes_id')
                    ->join('turnos as t6', 't6.id', '=', 't5.turnos_id')
                    ->where('t4.estudantes_id', $estudanteId)
                    ->select('t1.name as escola', 't3.nome as rota', 't6.nome as turno')
                    ->first();

                    // Verificar correspondência entre motorista e estudante
                    if ($DadosEstudante->escola !== $DadosMotorista->escola) {
                        return redirect()->back()->with('error', 'Estudante pertence a outra Instituição.');
                    }
                
                    if ($DadosEstudante->turno !== $DadosMotorista->turno) {
                        return redirect()->back()->with('error', 'Estudante pertence a outro turno.');
                    }
                
                    if ($DadosEstudante->rota !== $DadosMotorista->rota) {
                        return redirect()->back()->with('error', 'Estudante pertence a outra rota.');
                    }

                    // Verificar se já existe uma viagem em andamento
                    $ViagemAndamento = DB::table('viagems')
                    ->where('motoristas_id', $motorista->id)
                    ->where('estado', 2)
                    ->exists();

                    if ($ViagemAndamento) {
                        return redirect()->back()->with('error', 'O motorista tem uma viagem em andamento.');
                    }

                    // Buscar ou criar a viagem ativa
                    $ViagemAtiva = DB::table('viagems')
                    ->where('motoristas_id', $motorista->id)
                    ->where('estado', 1)
                    ->select('id')
                    ->first();

                    if (!$ViagemAtiva) {
                        $ViagemAtiva = DB::table('viagems')->insertGetId([
                            'motoristas_id' => $motorista->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        $ViagemAtiva = $ViagemAtiva->id;
                    }

                    // Verificar se o estudante já está a bordo
                    $Abordo = DB::table('dados_viagems')
                        ->where('estudantes_id', $id)
                        ->where('viagems_id', $ViagemAtiva)
                        ->exists();

                    if ($Abordo) {
                        return redirect()->back()->with('error', 'Estudante já foi adicionado.');
                    }

                    // Verificar capacidade do veículo
                    $capacidadeAtual = DB::table('dados_viagems')
                        ->where('viagems_id', $ViagemAtiva)
                        ->whereNotNull('estudantes_id')
                        ->count();

                    if ($capacidadeAtual >= $DadosMotorista->capacidade) {
                        return redirect()->back()->with('error', 'Veículo lotado.');
                    }

                    // Adicionar estudante à viagem
                    $resultado = DB::table('dados_viagems')->insert([
                        'estudantes_id' => $id,
                        'viagems_id' => $ViagemAtiva,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if ($resultado) {
                        $estudante = estudante::find($estudanteId);
                        StudentEventNotification::notifyResponsaveis('enter', $estudante);
                        EstudanteEventNotification::NotifyEstudante('enter', $estudanteId);
                        return redirect()->route('TelaMotorista')->with('sucess', 'Estudante adicionado a bordo.');
                    }

                    return redirect()->back()->with('error', 'Erro ao adicionar estudante.');

                }

            }
        }else{
            return redirect()->back()->with('error', 'A viagem não pode ser iniciada fora do horário de trabalho');
        }

        return redirect()->back()->with('error', 'Horário inválido para adicionar estudante.');
    } 

    public function removerEstudanteAbordo($id) {
        $motorista = Auth::user()->motorista;
    
        // Buscar a viagem ativa do motorista
        $viagemAtiva = DB::table('viagems')
            ->where('motoristas_id', $motorista->id)
            ->where('estado', 1)
            ->first();
    
        if (!$viagemAtiva) {
            return redirect()->back()->with('error', 'Nenhuma viagem ativa encontrada.');
        }
    
        // Verificar se o estudante está a bordo
        $estudanteAbordo = DB::table('dados_viagems')
            ->where('estudantes_id', $id)
            ->where('viagems_id', $viagemAtiva->id)
            ->exists();
    
        if (!$estudanteAbordo) {
            return redirect()->back()->with('error', 'Estudante não está a bordo.');
        }
    
        // Remover o estudante (setar estudantes_id como NULL)
        DB::table('dados_viagems')
            ->where('estudantes_id', $id)
            ->where('viagems_id', $viagemAtiva->id)
            ->delete();

            $estudante = estudante::find($id);
        
        StudentEventNotification::notifyResponsaveis('exit', $estudante);
        EstudanteEventNotification::NotifyEstudante('exit', $id);            
        return redirect()->back()->with('success', 'Estudante removido com sucesso.');
    }

    public function EnviarRelatorio(Request $request){
        
        // Validação simples
        $request->validate([
            'relatorio' => 'required|string|max:1000',
        ]);
        
        // Obter o motorista logado
        $motorista = Auth::user()->motorista;
        
        // Verificar se existe uma viagem ativa para o motorista
        $viagemAtiva = DB::table('viagems')
            ->where('motoristas_id', $motorista->id)
            ->whereIn('estado', [1, 2])
            ->first();
    
        
        if (!$viagemAtiva) {
            return redirect()->back()->with('error', 'Nenhuma viagem ativa encontrada para registrar o relatório.');
        }
        
        // Registrar o relatório na tabela dados_viagems

        $inserido = DB::table('dados_viagems')->insert([
            'viagems_id'   => $viagemAtiva->id,
            'relatorio'    => $request->input('relatorio'),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
        
        if ($inserido) {
            return redirect()->back()->with('sucess', 'Relatório enviado com sucesso.');
        }
        
        return redirect()->back()->with('error', 'Erro ao registrar o relatório.');
    }

    public function InfoResponsavel($id){

        $responsavel = DB::table('responsavels as t1')
        ->join('users as t2','t2.id','=','t1.users_id')
        ->select(
            't1.id',
            't2.name as Nome',
            't1.DataNascimento',
            't2.email',
            't1.endereco',
            't1.telefone',
            't1.foto'
        )
        ->where('t1.id',$id)->first();

        if(!$responsavel){
            return redirect()->back()->with('error', 'Responsável não encontrado.');
        }

        return view('Responsavel.InfoResponsavel', compact('responsavel'));
    }

    public function EscolasConexoes($id){

        $estudanteId = Auth::user()->estudante->id;

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
            $estado = DB::table('escolas_estudantes')
                    ->where('escolas_id', $SearchEscola->id)
                    ->where('estudantes_id',$estudanteId)
                    ->select('estado')
                    ->first();
        }

        return view('Estudante.EscolasConexoes', compact('SearchEscola','estado'));
    }

    public function AcaoEscolaConexao($id, $acao){

        $estudanteId = Auth::user()->estudante->id;


        $escola = DB::table('escolas_estudantes')
                ->where('escolas_id', $id)
                ->where('estudantes_id', $estudanteId)
                ->where('estado', 2)
                ->select('id')
                ->first();
        if(!$escola){
            return redirect()->back()->with('error', 'Erro ao localizar a escola.');
        }

        $rota = DB::table('estudantes_rotas as t1')
                ->join('rotas as t2','t2.id','=','t1.rotas_id')
                ->where('t1.estudantes_id', $estudanteId)
                ->where('t2.escolas_id', $id)   
                ->where('t1.estados', 2)
                ->select('t1.id')
                ->first();
        if(!$rota){
            return redirect()->back()->with('error', 'Erro ao passar a rota.');
        }

        if($acao == 'aceitar'){
            DB::table('escolas_estudantes')
                ->where('id', $escola->id)
                ->update(['estado' => 1]);

            DB::table('estudantes_rotas')
                ->where('id', $rota->id)
                ->update(['estados' => 1]);

            $pedidosPendentes = DB::table('escolas_estudantes')
                ->where('estudantes_id', $estudanteId)
                ->where('estado', 2)
                ->get();
            $rotasPendentes = DB::table('estudantes_rotas')
                ->where('estudantes_id', $estudanteId)
                ->where('estados', 2)
                ->get();

            if($pedidosPendentes){
                foreach($pedidosPendentes as $pedido){
                    DB::table('escolas_estudantes')
                        ->where('id', $pedido->id)
                        ->delete();
                }
            }

            if($rotasPendentes){
                foreach($rotasPendentes as $rota){
                    DB::table('estudantes_rotas')
                        ->where('id', $rota->id)
                        ->delete();
                }
            }

            return redirect()->back()->with('success', 'Conexão aceita com sucesso.');
        } else if($acao == 'negar'){
            DB::table('escolas_estudantes')
                ->where('id', $escola->id)
                ->delete();

            DB::table('estudantes_rotas')
                ->where('id', $rota->id)
                ->delete();

            return redirect()->back()->with('success', 'Conexão recusada com sucesso.');
        }

        return redirect()->back()->with('error', 'Ação inválida.');
    }
     
}
