<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResponsavelController extends Controller
{
    public function MainResponsavel(){
        
        return view('Responsavel.MainResponsavel');
    }
}
