@extends('layouts.main')
@section('title','Dashboard')
@section('content')
<!-- Container principal da Dashboard -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Dashboard de Estudantes</h2>
    
    <!-- Barra de Pesquisa -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-6 col-sm-8 col-10">
            <input type="text" class="form-control" placeholder="Pesquisar estudante...">
        </div>
    </div>
    
    <!-- Tabela de Estudantes -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Curso</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>João Silva</td>
                    <td>joao.silva@example.com</td>
                    <td>Engenharia</td>
                    <td>
                        <button class="btn btn-sm btn-primary mb-1">Editar</button>
                        <button class="btn btn-sm btn-danger mb-1">Deletar</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Maria Oliveira</td>
                    <td>maria.oliveira@example.com</td>
                    <td>Medicina</td>
                    <td>
                        <button class="btn btn-sm btn-primary mb-1">Editar</button>
                        <button class="btn btn-sm btn-danger mb-1">Deletar</button>
                    </td>
                </tr>
                <!-- Adicione mais linhas conforme necessário -->
            </tbody>
        </table>
    </div>

    <!-- Botão de Cadastrar Novo Estudante -->
    <div class="text-center mt-4">
        <a class="btn btn-success" href="/Estudante/CadastrarEstudante">Cadastrar Novo Estudante</a>
    </div>
</div>
<div id="space"></div>
@endsection