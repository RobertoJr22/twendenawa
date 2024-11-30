<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EstudanteController extends Controller
{
    public function index(){
        return view('Estudante.CadastrarEstudante');
    }

    public function MainEstudante(){
        return view('Estudante.MainEstudante');
    }
}
