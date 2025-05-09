<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\motorista;
use App\Models\User;
use App\Models\motoristas_rotas_veiculos;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Notifications\StudentEventNotification; // Adjust the namespace if necessary
use App\Notifications\EstudanteEventNotification; // Import the missing class
use App\Http\Requests\StoreMotoristaRequest;
use App\Models\carteira;
use App\Models\turno;
use App\Models\viagem;
use Carbon\Carbon;

class MotoristaController extends Controller
{
    public function index(){

        return view('auth.MotoristaRegister');
    }

    public function MainMotorista() {
        $user = Auth::user();
        $motorista = $user->motorista;
        $carteira = $motorista->carteira;
        $turno = $motorista->turno;
        $rota = $motorista->rota;
        $motoristaId = $motorista->id;
    
        $escola = DB::table('escolas as t1')
            ->join('escolas_motoristas as t2', 't2.escolas_id', '=', 't1.id')
            ->join('users as t3', 't3.id', '=', 't1.users_id')
            ->join('bairros as t4', 't4.id', '=', 't1.bairros_id')
            ->join('distritos as t5', 't5.id', '=', 't4.distritos_id')
            ->join('municipios as t6', 't6.id', '=', 't5.municipios_id')
            ->whereIn('t2.estado', [1, 2]) // Verificando se o motorista está ativo ou em andamento
            ->where('t2.motoristas_id', '=', $motorista->id)
            ->select(
                't1.id',
                't3.name as nome',
                't1.telefone',
                't3.email as email',
                't6.nome as municipio',
                't5.nome as distrito',
                't4.nome as bairro'
            )
            ->first();
    
        $dados = motoristas_rotas_veiculos::where('motoristas_id', $motorista->id)
            ->where('estado', 1)
            ->with([
                'rota.escola',         // Escola associada à rota
                'veiculo.escola',      // Escola associada ao veículo
                'veiculo.modelo.marcas', // Marca do veículo via modelo
                'motorista.carteira'   // Carteira do motorista
            ])
            ->first(); // Pegando apenas um resultado
    
        $aBordo = DB::table('dados_viagems as t1')
            ->where('t1.estado', '=', 1)
            ->join('estudantes as t2', 't2.id', '=', 't1.estudantes_id')
            ->join('users as t3', 't3.id', '=', 't2.users_id')
            ->join('viagems as t4', 't4.id', '=', 't1.viagems_id')
            ->where('t4.estado', '=', 1)
            ->select(
                't2.id',
                't3.name as nome'
            )
            ->get();
    
        // Recupera a viagem associada ao motorista
        $viagem = viagem::where('motoristas_id', $motorista->id)
            ->whereIn('estado', [1, 2]) // Verificando se a viagem está ativa ou em andamento
            ->first();
    
        return view('Motorista.MainMotorista', compact('user','motoristaId', 'motorista', 'dados', 'carteira', 'turno', 'rota', 'aBordo', 'escola', 'viagem'));
    }
    

    public function RegistrarMotorista(StoreMotoristaRequest $request){

        DB::beginTransaction();
        try{
            $imagePath = null;
            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
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

            $motorista = new motorista();
            $motorista->users_id = $user->id;
            $motorista->foto = $imagePath;
            $motorista->DataNascimento = $request->DataNascimento;
            $motorista->BI = $request->BI;
            $motorista->endereco = $request->endereco;
            $motorista->telefone = $request->telefone;
            $motorista->sexos_id = $request->sexos_id;
            $motorista->turnos_id = $request->turnos_id;
            $motorista->save();

            $carteira = new carteira();
            $carteira->NumeroCarta = $request->NumeroCarta;
            $carteira->motoristas_id = $motorista->id;
            $carteira->save();

            DB::commit();
            
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('erro', 'Erro ao cadastrar Motorista: ' . $e->getMessage());
        }

        Auth::login($user);
        return redirect()->route('TelaMotorista')->with('sucess','Bem vindo ao Twendenawa');
    }

    public function ComecarViagem(){

        $motorista = Auth::user()->motorista;
        $hora = now()->format('H');

        $Horaturno = DB::table('turnos as t1')
        ->where('t1.id','=',$motorista->turnos_id)
        ->select('t1.HoraIda','t1.HoraRegresso')
        ->first();

        $HoraIda = \Carbon\Carbon::parse($Horaturno->HoraIda)->format('H');
        $HoraRegresso = \Carbon\Carbon::parse($Horaturno->HoraRegresso)->format('H');

        if($hora >= $HoraIda && $hora <= $HoraRegresso){
            if($hora == $HoraIda || $hora == $HoraRegresso){
                $ViagemId = DB::table('viagems as t1')
                ->where('t1.motoristas_id','=',$motorista->id)
                ->where('t1.estado','=',1)
                ->value('t1.id');

                if($ViagemId == null){
                    return redirect()->route('TelaMotorista')->with('error','Não existe nenhuma viagem para iniciar, precisa adicionar estudantes a bordo');
                }

                $estudantes = DB::table('dados_viagems as t1')
                ->join('viagems as t2','t1.viagems_id','=','t2.id')
                ->where('t2.id',$ViagemId)
                ->whereNotNull('estudantes_id')
                ->count();

                if($estudantes == 0){
                    return redirect()->route('TelaMotorista')->with('error','Não existe nenhum estudante a bordo');
                }

                $comecar = DB::table('viagems as t1')
                ->where('t1.id','=',$ViagemId)
                ->update(['estado'=>2]);

                if ($comecar > 0) {
                    try {
                        $idsEstudantes = DB::table('dados_viagems')
                            ->where('viagems_id', $ViagemId)
                            ->whereNotNull('estudantes_id')
                            ->pluck('estudantes_id');
                    
                        $estudantes = \App\Models\Estudante::whereIn('id', $idsEstudantes)->get();
                    
                        foreach ($estudantes as $estudante) {
                            // Enviar a notificação para os responsáveis
                            StudentEventNotification::notifyResponsaveis('trip_start', $estudante);
                            //enviar para os estudantes
                            EstudanteEventNotification::NotifyEstudante('trip_start', $estudante->id);
                        }
                    
                        return redirect()->route('TelaMotorista')->with('success', 'Viagem iniciada com sucesso');
                    } catch (\Exception $e) {
                        // Caso ocorra um erro no envio da notificação ou em outra parte do código
                        return redirect()->route('TelaMotorista')->with('error', 'Erro ao iniciar a viagem. Tente novamente.');
                    }
                    
                } else {
                    return redirect()->route('TelaMotorista')->with('error', 'Erro ao iniciar a viagem ou já está em andamento');
                }

            }else{
                return redirect()->route('TelaMotorista')->with('error','A viagem não pode ser iniciada fora do horário de trabalho');
            }
        }else{
            return redirect()->route('TelaMotorista')->with('error','A viagem não pode ser iniciada fora do horário de trabalho');
        }
    }
    
    public function TerminarViagem(){
        $motorista = Auth::user()->motorista;
        $Horaturno = DB::table('turnos as t1')
            ->where('t1.id', '=', $motorista->turnos_id)
            ->select('t1.HoraIda','t1.HoraRegresso')
            ->first();
    
        $HoraIda = \Carbon\Carbon::parse($Horaturno->HoraIda)->format('H');
        $HoraRegresso = \Carbon\Carbon::parse($Horaturno->HoraRegresso)->format('H');
        $HoraAgora = \Carbon\Carbon::now()->format('H');
    
        if($HoraAgora == $HoraIda){
            $ViagemAtiva = DB::table('viagems')
                ->select('id')
                ->where('motoristas_id', $motorista->id)
                ->where('estado', 2)
                ->first();
    
            if($ViagemAtiva){
                $Estudantes = DB::table('dados_viagems as t1')
                    ->join('viagems as t2','t1.viagems_id','=','t2.id')
                    ->where('t2.id', '=', $ViagemAtiva->id)
                    ->where('t2.estado', '=', 2)
                    ->whereNotNull('estudantes_id')
                    ->pluck('estudantes_id');
    
                if($Estudantes->count()){
                    foreach($Estudantes as $id){
                        $estudante = \App\Models\Estudante::find($id);
                        if ($estudante) {
                            StudentEventNotification::notifyResponsaveis('trip_end', $estudante);
                            EstudanteEventNotification::NotifyEstudante('trip_end', $estudante->id);
                        }
                    }
                }
    
                DB::table('dados_viagems')
                    ->where('viagems_id', $ViagemAtiva->id)
                    ->update(['estado' => 0]);
    
                DB::table('viagems')
                    ->where('id', $ViagemAtiva->id)
                    ->update(['estado' => 3]);
    
                return redirect()->route('TelaMotorista')->with('success','Viagem terminada com sucesso');
            } else {
                return redirect()->route('TelaMotorista')->with('alert','Não existe nenhuma viagem ativa para terminar');
            }
        }elseif($HoraAgora == $HoraRegresso){
            $ViagemAtiva = DB::table('viagems')
                ->select('id')
                ->where('motoristas_id', $motorista->id)
                ->where('estado', 2)
                ->first();
    
            if($ViagemAtiva){
                $Estudantes = DB::table('dados_viagems as t1')
                    ->join('viagems as t2','t1.viagems_id','=','t2.id')
                    ->where('t2.id', '=', $ViagemAtiva->id)
                    ->where('t2.estado', '=', 2)
                    ->whereNotNull('estudantes_id')
                    ->pluck('estudantes_id');
    
                if($Estudantes->count()){
                    foreach($Estudantes as $id){
                        $estudante = \App\Models\Estudante::find($id);
                        if ($estudante) {
                            StudentEventNotification::notifyResponsaveis('trip_end', $estudante);
                            EstudanteEventNotification::NotifyEstudante('trip_end', $estudante->id);
                        }
                    }
                }
    
                DB::table('dados_viagems')
                    ->where('viagems_id', $ViagemAtiva->id)
                    ->update(['estado' => 0]);
    
                DB::table('viagems')
                    ->where('id', $ViagemAtiva->id)
                    ->update(['estado' => 0]);
    
                return redirect()->route('TelaMotorista')->with('success','Viagem terminada com sucesso');
            } else {
                return redirect()->route('TelaMotorista')->with('alert','Não existe nenhuma viagem ativa para terminar');
            }
        }else{
            return redirect()->route('TelaMotorista')->with('alert','Fora do horário de trabalho');
        }
    }
    

    public function InfoEstudanteAbordo($estudanteId){
        $motorista = Auth::user()->motorista;
        $estudantes = DB::table('estudantes as t1')
        ->join('users as t2', 't2.id', '=', 't1.users_id')
        ->join('turnos as t4', 't4.id', '=', 't1.turnos_id')
        ->leftJoin('estudantes_rotas as t5', 't5.estudantes_id', '=', 't1.id')
        ->leftJoin('rotas as t6', 't6.id', '=', 't5.rotas_id')
        ->leftJoin('escolas as t7', 't7.id', '=', 't6.escolas_id')
        ->leftJoin('users as t8', 't8.id', '=', 't7.users_id')
        ->where('t1.id', $estudanteId)
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
        ->where('t1.estudantes_id',$estudanteId)
        ->get();

        return view('Estudante.InfoEstudante',compact('estudantes', 'responsaveis'));	
    }
}
