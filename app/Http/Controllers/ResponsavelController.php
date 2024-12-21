<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResponsavelRequest;
use app\Models;
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
            $user = User::create([
                'name'=>$request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Criptografando a senha
                'tipo_usuario_id' => $request->tipo_usuario_id,
            ]);

            // Verifica se a foto foi enviada
            $path = $request->hasFile('foto') ? $request->file('foto')->store('fotos', 'public') : null;
            dd($path);

            responsavel::create([
                'users_id'=>$user->id,
                'foto'=>$path,
                'DataNascimento'=>$request->DataNascimento,
                'BI'=>$request->BI,
                'telefone'=>$request->telefone,
                'endereco'=>$request->endereco,
                'sexos_id'=>$request->sexos_id,
            ]);

            DB::commit();
        }catch(\Exception $e ){
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao cadastrar: ' . $e->getMessage());
        }
        
        Auth::login($user);
        return redirect()->route('TelaResponsavel');

    }


    public function MainResponsavel(){
        
        return view('Responsavel.MainResponsavel');
    }
}
