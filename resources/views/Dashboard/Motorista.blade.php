@extends('layouts.main')
@section('title','Dashboard')
@section('content')
<!-- Container principal da Dashboard de Motoristas -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Dashboard de Motoristas</h2>

    <!-- Barra de Pesquisa de Motoristas -->
    <div class="row justify-content-center mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Pesquisar motorista...">
        </div>
    </div>

    <!-- Tabela de Motoristas -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Licença</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Pedro Santos</td>
                    <td>(99) 99999-9999</td>
                    <td>12345-AB</td>
                    <td>
                        <button class="btn btn-sm btn-custom">Editar</button>
                        <button class="btn btn-sm btn-custom">Deletar</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Lucas Pereira</td>
                    <td>(88) 88888-8888</td>
                    <td>67890-CD</td>
                    <td>
                        <button class="btn btn-sm btn-custom">Editar</button>
                        <button class="btn btn-sm btn-custom">Deletar</button>
                    </td>
                </tr>
                <!-- Adicione mais linhas conforme necessário -->
            </tbody>
        </table>
    </div>

    <!-- Botão de Cadastrar Novo Motorista -->
    <div class="text-center mt-3">
        <a href="/Motorista/CadastrarMotorista" class="btn btn-custom">Cadastrar Novo Motorista</a>
    </div>
</div>
<div id="space"></div>
@endsection