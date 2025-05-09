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

    /* Motorista */
    Route::get('/Motorista/MainMotorista',[MotoristaController::class, 'MainMotorista'])->name('TelaMotorista');
    Route::get('/Estudante/SelecaoEstudante',[EstudanteController::class,'SelecaoEstudante'])->name('selecaoEstudante');
    Route::get('/Estudante/{id}/adicionar', [EstudanteController::class, 'adicionarAbordo'])->name('AdicionarAbordo');
    Route::get('/Estudante/{id}/remover', [EstudanteController::class, 'removerEstudanteAbordo'])->name('RemoverAbordo');
    Route::post('/EnviarRelatorio/Viagem', [EstudanteController::class, 'EnviarRelatorio'])->name('EnviarRelatorio');
    Route::get('InfoEstudanteAbordo/{id}',[MotoristaController::class,'InfoEstudanteAbordo'])->name('InfoEstudanteAbordo');
    Route::get('IniciarViagem', [MotoristaController::class, 'ComecarViagem'])->name('ComecarViagem');
    Route::get('FinalizarViagem', [MotoristaController::class, 'TerminarViagem'])->name('TerminarViagem');

    /* Estudante*/
    Route::get('/Estudante/CadastrarEstudante',[EstudanteController::class, 'index'] );
    Route::get('/Estudante/MainEstudante',[EstudanteController::class, 'MainEstudante'] )->name('TelaEstudante');
    Route::get('/Estudante/PagamentosEstudante',[EstudanteController::class,'PagamentosEstudante']);
    Route::get('/Responsavel/InfoResponsavel/{id}',[EstudanteController::class,'InfoResponsavel'])->name('InfoResponsavel');
    Route::get('/Estudante/EscolaConexoes/{id}',[EstudanteController::class,'EscolasConexoes'])->name('EscolasConexoes');
    Route::get('/Estudante/EscolaConexoes/{id}/{acao}', [EstudanteController::class, 'AcaoEscolaConexao'])->name('AcaoEscolaConexao');
    Route::get('/DesvincularEscola/{id}', [EstudanteController::class, 'DesvincularEscola'])->name('DesvincularEscola');

    /* Escola */
    Route::get('/Escola/MainEscola',[EscolaController::class,'index'])->name('TelaEscola');
    Route::get('/Escola/Estudante',[EscolaController::class,'ListaEstudante'])->name('ListaEstudante');
    Route::post('/Escola/Estudante',[EscolaController::class,'ListaEstudante'])->name('BuscaEstudante');
    Route::get('/Escola/Estudante/CadastrarEstudante',[EscolaController::class, 'ExibirCadastrarEstudante'])->name('ExibirCadastrarEstudante');
    Route::post('/Escola/Estudante/CadastrarEstudante',[EscolaController::class, 'CadastrarEstudante'])->name('InscreverEstudante');
    Route::get('/Escola/Responsavel',[EscolaController::class,'Responsavel']);
    Route::get('/Escola/Viatura',[EscolaController::class,'Viatura']);
        //escola rotas
    Route::get('/Escola/Rota/CadastrarRota', [EscolaController::class, 'ExibirCadastrarRota']);
    Route::post('/Escola/Rota/CadastrarRota', [EscolaController::class, 'CadastrarRota'])->name('CadastrarRota');
    Route::get('/Escola/Rota/ListaRota',[EscolaController::class,'ListaRota'])->name('ListaRota');
        //escola veiculos
    Route::get('/Escola/Veiculo/CadastrarVeiculo',[EscolaController::class, 'ExibirCadastrarVeiculo'])->name('ExibirCadastrarVeiculo');
    Route::post('/Escola/Veiculo/CadastrarVeiculo',[EscolaController::class, 'CadastrarVeiculo'])->name('CadastrarVeiculo');
    Route::get('/Escola/Veiculo/ListaVeiculo',[EscolaController::class,'ListaVeiculo'])->name('ListaVeiculo');
    Route::get('/modelos/{marcaId}', [EscolaController::class, 'getModelos']);
        //escola motoristas
    Route::get('/Escola/Motorista/ListaMotorista',[EscolaController::class, 'ListaMotorista'])->name('ListaMotorista');
    Route::get('/Escola/Motorista/AdAssociar',[EscolaController::class, 'ExibirAdAssociar'])->name('AdAssociar');
    Route::post('/Escola/Motorista/AdAssociar',[EscolaController::class, 'Associar'])->name('Associar');
    Route::get('/DesassociarMotorista/{id}',[EscolaController::class, 'DesassociarMotorista'])->name('DesassociarMotorista');
    Route::get('/DesvincularMotorista/{id}',[EscolaController::class, 'DesvincularMotorista'])->name('DesvincularMotorista');

    /* Responsavel */
    Route::get('/Responsavel/MainResponsavel',[ResponsavelController::class, 'MainResponsavel'])->name('TelaResponsavel');
    Route::get('/Estudante/DetalhesViagem/{id}',[ResponsavelController::class,'DetalhesViagem'])->name('DetalhesViagem');
    Route::get('/Estudante/InfoEstudante/{id}',[ResponsavelController::class,'InfoEstudante'])->name('InfoEstudante');
    Route::get('/Responsavel/conexao/{id?}',[ResponsavelController::class,'ExibirConexao'])->name('ExibirConexao');
    Route::get('/Responsavel/MainResponsavel/{id}',[ResponsavelController::class,'DesfazerConexao'])->name('DesfazerConexao');
    Route::get('/AcaoConexao/{id}/{acao}/{tipousuario}', [ResponsavelController::class, 'AcaoConexao'])->name('AcaoConexao');


    /*Admin*/
    Route::get('/Admin/TelaAdm',[TwendenawaController::class,'TelaAdmin'])->name('TelaAdmin')->middleware('auth');
    Route::get('/distritos/{municipiosId}',[TwendenawaController::class,'getDistritos']); //preencher input de distritos
    Route::get('/bairros/{distritosId}',[TwendenawaController::class,'getBairros']); //preencher input de bairros
    Route::get('/Admin/CadastrarEscola',[TwendenawaController::class,'ExibirCadastrarEscola']);
    Route::post('/Admin/CadastrarEscola',[TwendenawaController::class,'CadastrarEscola'])->name('CadastrarEscola');
    Route::get('/Admin/ListaEscola',[TwendenawaController::class, 'ExibirListaEscola'])->name('ListaEscola');
    Route::delete('/escola/{id}',[TwendenawaController::class, 'DeletarEscola'])->name('DeletarEscola');

});

/* AUTENTICACAO ou login */
Route::get('/auth/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/auth/login', [AuthController::class, 'login']);

/* Registro de Motoristas */
Route::get('/auth/MotoristaRegister',[AuthController::class,'showMotoristaForm'])->name('TelaRegistoMotorista');
Route::post('/auth/MotoristaRegister',[MotoristaController::class, 'RegistrarMotorista'])->name('RegistarMotorista');

/* Registro de Responsaveis */
Route::get('/auth/ResponsavelRegister',[AuthController::class, 'showResponsavelForm'])->name('TelaRegistoResponsavel');
Route::post('auth/ResponsavelRegister', [ResponsavelController::class, 'ResponsavelRegister'])->name('RegistarResponsavel');

/* Registro de Estudantes */
Route::get('/auth/EstudanteRegister',[AuthController::class, 'showEstudanteForm'])->name('TelaRegistoEstudante');
Route::post('/auth/EstudanteRegister', [EstudanteController::class, 'store'])->name('RegistarEstudante');

/* Tela de selecao do tipo de usuario */
Route::get('/auth/Selecao',[AuthController::class, 'SelecaoRegisto'])->name('SelecaoRegisto');

/* Rota de logout*/
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/', function () {
    return view('welcome');
});

/* Twendenawa Rotas */
Route::get('/',[TwendenawaController::class, 'index']);





