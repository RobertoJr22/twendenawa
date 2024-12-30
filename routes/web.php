<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\EstudanteController;
use App\Http\Controllers\TwendenawaController;
use App\Http\Controllers\EscolaController;
use App\Http\Controllers\ResponsavelController;
use App\Http\Controllers\AuthController;

/* AUTENTICACAO */

Route::get('/auth/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/auth/login', [AuthController::class, 'login']);

//Route::get('/auth/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::get('/auth/ResponsavelRegister',[AuthController::class, 'showResponsavelForm'])->name('TelaRegistoResponsavel');
Route::post('auth/ResponsavelRegister', [ResponsavelController::class, 'ResponsavelRegister'])->name('RegistarResponsavel');

Route::get('/auth/EstudanteRegister',[AuthController::class, 'showEstudanteForm'])->name('TelaRegistoEstudante');
Route::post('/auth/EstudanteRegister', [EstudanteController::class, 'store'])->name('RegistarEstudante');
Route::get('/auth/Selecao',[AuthController::class, 'SelecaoRegisto'])->name('SelecaoRegisto');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/', function () {
    return view('welcome');
});

/* Twendenawa Rotas */
Route::get('/',[TwendenawaController::class, 'index']);

/* Motorista Rotas */
Route::get('/Motorista/CadastrarMotorista',[MotoristaController::class, 'index'] );
Route::get('/Motorista/MainMotorista',[MotoristaController::class, 'MainMotorista'])->name('TelaMotorista');

/* Estudante Rotas */
Route::get('/Estudante/CadastrarEstudante',[EstudanteController::class, 'index'] );
Route::get('/Estudante/MainEstudante',[EstudanteController::class, 'MainEstudante'] )->name('TelaEstudante');
Route::get('/Estudante/DetalhesViagem',[EstudanteController::class,'DetalhesViagem']);
Route::get('/Estudante/InfoEstudante',[EstudanteController::class,'InfoEstudante']);
Route::get('/Estudante/SelecaoEstudante',[EstudanteController::class,'SelecaoEstudante']);

/* Escola */
Route::get('/Escola/MainEscola',[EscolaController::class,'index'])->name('TelaEscola');
Route::get('/Escola/Estudante',[EscolaController::class,'Estudante'])->name('Escola.Estudante');
Route::get('/Escola/Responsavel',[EscolaController::class,'Responsavel']);
Route::get('/Escola/Viatura',[EscolaController::class,'Viatura']);
Route::get('/Escola/Motorista',[EscolaController::class,'Motorista']);

/* Responsavel Rotas */
//Usa os middlewares para permitir que apenas autorizados tenham acesso
Route::get('/Responsavel/MainResponsavel',[ResponsavelController::class, 'MainResponsavel'])->name('TelaResponsavel')->middleware('auth');