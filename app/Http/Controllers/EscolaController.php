<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EscolaController extends Controller
{

    public function index(){
        return view('Escola.MainEscola');
    }

    public function Estudante(){
        return view('Escola.Estudante');
    }

    public function Motorista(){
        return view('Escola.Motorista');
    }

    public function Responsavel(){
        return view('Escola.Responsavel');
    }

    public function Viatura(){
        return view('Escola.Viatura');
    }
}
