<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\EstudanteController;
use App\Http\Controllers\TwendenawaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResponsavelController;

Route::get('/', function () {
    return view('welcome');
});

/* Twendenawa Rotas */
Route::get('/',[TwendenawaController::class, 'index']);

/* Motorista Rotas */
Route::get('/Motorista/CadastrarMotorista',[MotoristaController::class, 'index'] );
Route::get('/Motorista/MainMotorista',[MotoristaController::class, 'MainMotorista'] );

/* Estudante Rotas */
Route::get('/Estudante/CadastrarEstudante',[EstudanteController::class, 'index'] );
Route::get('/Estudante/MainEstudante',[EstudanteController::class, 'MainEstudante'] );

/* Dashboard */
Route::get('/Dashboard/Dash',[DashboardController::class,'index']);
Route::get('/Dashboard/Estudante',[DashboardController::class,'Estudante']);
Route::get('/Dashboard/Responsavel',[DashboardController::class,'Responsavel']);
Route::get('/Dashboard/Viatura',[DashboardController::class,'Viatura']);
Route::get('/Dashboard/Motorista',[DashboardController::class,'Motorista']);

/* Responsavel Rotas */
Route::get('/Responsavel/MainResponsavel',[ResponsavelController::class, 'MainResponsavel']);