@extends('layouts.main')
@section('title','Dashboard')
@section('content')
    <!-- Container principal da Dashboard de Responsáveis -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Dashboard de Responsáveis dos Estudantes</h2>

        <!-- Barra de Pesquisa de Responsáveis -->
        <div class="row justify-content-center mb-3">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Pesquisar responsável...">
            </div>
        </div>

        <!-- Tabela de Responsáveis -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Carlos Ferreira</td>
                        <td>(99) 99999-9999</td>
                        <td>carlos.ferreira@example.com</td>
                        <td>
                            <button class="btn btn-sm btn-custom">Editar</button>
                            <button class="btn btn-sm btn-custom">Deletar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Ana Souza</td>
                        <td>(88) 88888-8888</td>
                        <td>ana.souza@example.com</td>
                        <td>
                            <button class="btn btn-sm btn-custom">Editar</button>
                            <button class="btn btn-sm btn-custom">Deletar</button>
                        </td>
                    </tr>
                    <!-- Adicione mais linhas conforme necessário -->
                </tbody>
            </table>
        </div>

        <!-- Botão de Cadastrar Novo Responsável -->
        <div class="text-center mt-3">
            <button class="btn btn-custom">Cadastrar Novo Responsável</button>
        </div>
    </div>
    <div id="space"></div>
@endsection