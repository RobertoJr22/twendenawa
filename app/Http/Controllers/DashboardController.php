<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index(){
        return view('Dashboard.Dash');
    }

    public function Estudante(){
        return view('Dashboard.Estudante');
    }

    public function Motorista(){
        return view('Dashboard.Motorista');
    }

    public function Responsavel(){
        return view('Dashboard.Responsavel');
    }

    public function Viatura(){
        return view('Dashboard.Viatura');
    }
}
