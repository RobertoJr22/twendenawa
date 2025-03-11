<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\motorista;
use App\Models\User;
use App\Models\motoristas_rotas_veiculos;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreMotoristaRequest;
use App\Models\carteira;
use App\Models\turno;

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
                    ->where('t1.estado','=',1)
                    ->join('estudantes as t2','t2.id','=','t1.estudantes_id')
                    ->join('users as t3','t3.id','=','t2.users_id')
                    ->join('viagems as t4','t4.id','=','t1.viagems_id')
                    ->where('t4.estado','=',1)
                    ->select(
                        't2.id',
                        't3.name as nome'
                    )->get();
                    
    
        
        return view('Motorista.MainMotorista', compact('user', 'motorista','dados', 'carteira','turno','rota','aBordo'));
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
    
}
