<?php

namespace App\Http\Controllers;

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
                    ->select('t2.name as nome','t1.id')
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
                        't8.nome as modelo',
                        't9.nome as marca',
                        't7.Matricula',
                        't4.name as motorista',
                        't10.PontoA',
                        't10.PontoB',
                        't5.HoraIda',
                        't5.HoraRegresso'
                    )
                    ->where('t1.estado', 1)
                    ->first();
        
        // Recupera as notificações do responsável, ordenando as mais recentes primeiro
        $notificacoes = $estudante->notifications()->orderBy('created_at', 'desc')->get();

        return view('Estudante.MainEstudante', compact('estudante','turno','rota', 'user', 'escola','responsaveis', 'notificacoes','viagem'));
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
        $estudante = Estudante::find($id);
        if (!$estudante) {
            return redirect()->back()->with('error', 'Erro de sistema, não foi possível localizar o estudante.');
        }
    
        // Verificar se o estudante tem uma instituição ativa
        $VerifEscola = estudantes_rotas::where('estudantes_id', $estudante->id)
            ->where('estados', 1)
            ->exists();
    
        if (!$VerifEscola) {
            return redirect()->back()->with('error', 'Estudante sem instituição.');
        }
    
        // Obter dados do estudante
        $DadosEstudante = DB::table('users as t1')
            ->join('escolas as t2', 't1.id', '=', 't2.users_id')
            ->join('rotas as t3', 't2.id', '=', 't3.escolas_id')
            ->join('estudantes_rotas as t4', 't3.id', '=', 't4.rotas_id')
            ->join('estudantes as t5', 't5.id', '=', 't4.estudantes_id')
            ->join('turnos as t6', 't6.id', '=', 't5.turnos_id')
            ->where('t4.estudantes_id', $estudante->id)
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
            return redirect()->back()->with('error', 'Tem uma viagem em andamento.');
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
            ->update(['estudantes_id' => null]);
    
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
     
}
