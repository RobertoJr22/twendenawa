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
        return redirect()->route('TelaResponsavel')->with('sucess','Responsavel cadastrado com sucesso!');

    }


    public function MainResponsavel(){
        
        $user = Auth::user();

        
        $responsavel = $user->responsavel; 
    
        return view('Responsavel.MainResponsavel', compact('user', 'responsavel'));
    }
}