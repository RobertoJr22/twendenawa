@extends('layouts.main')
@section('title','Estudantes')
@section('content')
<!-- Container principal da Dashboard -->
<div class="container mt-5">
    <h2 class="text-center mb-4 fs-responsive">Dashboard de Estudantes</h2>
    
    <!-- Barra de Pesquisa -->
    <div class="row justify-content-center mb-4">
        <form action="{{route('BuscaEstudante')}}" method="post">
            @csrf
            <div class="input-group mb-4">
                <input name="search" value="{{ request('search')}}" type="text" class="form-control" placeholder="Pesquisar estudante...">
                <button class="btn btn-primary" type="submit">Pesquisar</button>
            </div>
        </form>
    </div>

    <!-- Tabela de Estudantes -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Turno</th>
                    <th>Rota</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>               
                @foreach($busca as $b)
                <tr>
                    <td>{{$b->id}}</td>
                    <td>{{$b->nome}}</td>
                    <td>{{$b->email}}</td>
                    <td>{{$b->turno}}</td>
                    <td>{{$b->rota}}</td>
                    <td>{{$b->telefone}}</td>
                    <td>
                        <button class="btn btn-sm btn-custom mb-1">Editar</button>
                        <button class="btn btn-sm btn-custom mb-1">Deletar</button>
                    </td>
                </tr>
                @endforeach   
            </tbody>
        </table>
    </div>

    <!-- Botão de Cadastrar Novo Estudante -->
    <div class="text-center mt-4">
        <a class="btn btn-custom" href="{{ route('ExibirCadastrarEstudante') }}">Cadastrar Novo Estudante</a>
    </div>
</div>
<div id="space"></div>
@endsection