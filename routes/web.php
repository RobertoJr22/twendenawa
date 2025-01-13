<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\EstudanteController;
use App\Http\Controllers\TwendenawaController;
use App\Http\Controllers\EscolaController;
use App\Http\Controllers\ResponsavelController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\VerificarEstadoUsuario;


// Middleware para verificar o estado do usuario
Route::middleware(['auth', VerificarEstadoUsuario::class])->group(function () {
    // rotas protegidas aqui

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
        //escola rotas
    Route::get('/Escola/Rota/CadastrarRota', [EscolaController::class, 'ExibirCadastrarRota']);
    Route::post('/Escola/Rota/CadastrarRota', [EscolaController::class, 'CadastrarRota'])->name('CadastrarRota');
    Route::get('/Escola/Rota/ListaRota',[EscolaController::class,'ListaRota'])->name('ListaRota');
        //escola viaturas
    Route::get('/Escola/Veiculo/CadastrarVeiculo',[EscolaController::class, 'ExibirCadastrarVeiculo'])->name('ExibirCadastrarVeiculo');
    Route::post('/Escola/Veiculo/CadastrarVeiculo',[EscolaController::class, 'CadastrarVeiculo'])->name('CadastrarVeiculo');
    Route::get('/modelos/{marcaId}', [EscolaController::class, 'getModelos']);
    /* Responsavel Rotas */
    Route::get('/Responsavel/MainResponsavel',[ResponsavelController::class, 'MainResponsavel'])->name('TelaResponsavel');

    /*Admin*/
    Route::get('/Admin/TelaAdm',[TwendenawaController::class,'TelaAdmin'])->name('TelaAdmin')->middleware('auth');
    Route::get('/distritos/{municipiosId}',[TwendenawaController::class,'getDistritos']); //preencher input de distritos
    Route::get('/bairros/{distritosId}',[TwendenawaController::class,'getBairros']); //preencher input de bairros
    Route::get('/Admin/CadastrarEscola',[TwendenawaController::class,'ExibirCadastrarEscola']);
    Route::post('/Admin/CadastrarEscola',[TwendenawaController::class,'CadastrarEscola'])->name('CadastrarEscola');
    Route::get('/Admin/ListaEscola',[TwendenawaController::class, 'ExibirListaEscola'])->name('ListaEscola');
    Route::delete('/escola/{id}',[TwendenawaController::class, 'DeletarEscola'])->name('DeletarEscola');

});

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





