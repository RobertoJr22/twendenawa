<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TwendenawaController extends Controller
{
    public function index(){
        return view('welcome');
    }
}