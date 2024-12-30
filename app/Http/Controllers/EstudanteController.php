<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\estudante;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreStudentRequest;

class EstudanteController extends Controller
{
    public function index(){
        return view('Estudante.CadastrarEstudante');
    }

    public function MainEstudante(){
        return view('Estudante.MainEstudante');
    }

    public function DetalhesViagem(){
        return view('Estudante.DetalhesViagem');
    }

    public function store(StoreStudentRequest $request){
        
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
        
        $estudante = New estudante(); 
        $estudante->users_id = $user->id;
        $estudante->foto = $imagePath;
        $estudante->DataNascimento = $request->DataNascimento;
        $estudante->endereco = $request->endereco;
        $estudante->telefone = $request->telefone;
        $estudante->sexos_id = $request->sexos_id;
        $estudante->turnos_id = $request->turnos_id;
        $estudante->save();
        return redirect()->route('Escola.Estudante')->with('success', 'Estudante criados com sucesso!');
    }


    public function InfoEstudante(){
        return view('Estudante.InfoEstudante');
    }

    public function SelecaoEstudante(){
        return view('Estudante.SelecaoEstudante');
    }
}