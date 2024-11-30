@extends('layouts.main')
@section('title','Dashboard')
@section('content')
    <!-- Container principal da Dashboard de Viaturas -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Dashboard de Viaturas</h2>

        <!-- Barra de Pesquisa de Viaturas -->
        <div class="row justify-content-center mb-3">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Pesquisar viatura...">
            </div>
        </div>

        <!-- Tabela de Viaturas -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Placa</th>
                        <th>Modelo</th>
                        <th>Ano</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>ABC-1234</td>
                        <td>Ford Ranger</td>
                        <td>2021</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Editar</button>
                            <button class="btn btn-sm btn-danger">Deletar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>XYZ-5678</td>
                        <td>Chevrolet Onix</td>
                        <td>2019</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Editar</button>
                            <button class="btn btn-sm btn-danger">Deletar</button>
                        </td>
                    </tr>
                    <!-- Adicione mais linhas conforme necessário -->
                </tbody>
            </table>
        </div>

        <!-- Botão de Cadastrar Nova Viatura -->
        <div class="text-center mt-3">
            <button class="btn btn-success">Cadastrar Nova Viatura</button>
        </div>
    </div>
    <div id="space"></div>
@endsection