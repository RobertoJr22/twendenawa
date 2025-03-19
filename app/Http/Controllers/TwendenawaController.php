<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use app\Models;
use App\Models\bairro;
use App\Models\distrito;
use App\Models\municipio;
use App\Http\Requests\StoreEscolaRequest;
use App\Models\escola;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TwendenawaController extends Controller
{
    public function index(){

        if(Auth::check()){
            $user = Auth::user();
            if($user->tipo_usuario_id == 1){
                return redirect()->route('TelaAdmin');
            }elseif($user->tipo_usuario_id == 2){
                return redirect()->route('TelaEstudante');
            }elseif($user->tipo_usuario_id == 3){
                return redirect()->route('TelaMotorista');
            }elseif($user->tipo_usuario_id == 4){
                return redirect()->route('TelaResponsavel');
            }elseif($user->tipo_usuario_id == 5){
                return redirect()->route('TelaEscola');
            }
        }else{
            return view('welcome');
        }
    }

    public function TelaAdmin(){
        return view('Admin.TelaAdm');
    }

    public function getDistritos($MunicipiosId){
        $distritos = distrito::where('municipios_id', $MunicipiosId)->get(); 
        return response()->json($distritos);
    }
    
    public function getBairros($DistritosId){
        $bairros = bairro::where('distritos_id', $DistritosId)->get(); 
        return response()->json($bairros);
    }
    

    public function ExibirCadastrarEscola(){

        $municipios = municipio::all();

        return view('Admin.CadastrarEscola', compact('municipios'));
    }

    public function ExibirListaEscola(Request $request){

        $search = $request->input('search');

        if($search){
            
            $escolas = escola::with('user','bairro')
            ->whereRelation('bairro', 'nome', 'like', '%' . $search . '%') // Filtrar pelo nome do bairro
            ->orWhereRelation('user', 'name', 'like', '%' . $search . '%') // Filtrar pelo nome do usuário
            ->get();

        }else{
            $escolas = escola::with('user','bairro')->get();
        }

        return view('Admin.ListaEscola', compact('escolas'));
    }

    public function CadastrarEscola(StoreEscolaRequest $request){
        DB::beginTransaction();
        try{
            $imagePath = null;
            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                $imagePath = $request->file('foto')->store('avatares', 'public');
            }

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->tipo_usuario_id = $request->tipo_usuario_id;
            $user->username = User::generateUniqueUsername($user->name);
            $user->save();

            $escola = new escola();
            $escola->users_id = $user->id;
            $escola->foto = $imagePath;
            $escola->bairros_id = $request->bairros_id;
            $escola->telefone = $request->telefone;
            $escola->save();

            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao cadastrar: ' . $e->getMessage());
        }

        return redirect()->route('ListaEscola')->with('sucess','Escola cadastrada com sucesso ');
    }

    public function DeletarEscola($id){
        $escola = escola::findOrFail($id);

        if($escola){
            DB::beginTransaction();
            try{
                // Atualiza o estado na tabela escolas
                DB::table('escolas')
                ->where('id', $id)
                ->update(['estado' => 0]);

                // Atualiza o estado na tabela usuarios, usando o usuario_id da escola
                DB::table('users')
                    ->where('id', $escola->users_id) // Usa a chave estrangeira usuario_id
                    ->update(['estado' => 0]);

                DB::commit();
                return redirect()->route('ListaEscola')->with('sucess','Escola excluida com sucesso');
            }catch(\Exception $e){
                DB::rollBack();
                return redirect()->route('ListaEscola')->with('error','Ocorreu um erro:'.$e->getMessage());
            }
        }else{
            return redirect()->route('ListaEscola')->with('error','Escola nao existe na base de dados');
        }
    
    }
}