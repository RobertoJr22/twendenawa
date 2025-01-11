<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use app\Models\User;
use app\Models\motorista;


class MotoristaController extends Controller
{
    public function index(){

        return view('Motorista.CadastrarMotorista');
    }

    public function MainMotorista(){

        $user = Auth::user();
        $motorista = $user->motorista;
        
        return view('Motorista.MainMotorista', compact('user','motorista'));
    }
}
