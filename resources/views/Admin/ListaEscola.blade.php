@extends('layouts.main')
@section('title','Escolas')
@section('content')
<!-- Container principal da Dashboard -->
<div class="container mt-5">
    <h2 class="text-center mb-4 fs-responsive">Lista de Escolas</h2>
    
    <!-- Barra de Pesquisa -->
     <form action="{{route('ListaEscola')}}" method="get">
        @csrf
        <div class="row justify-content-center mb-4">
            <div class="col-md-6 col-sm-8 col-10">
                <input type="text" class="form-control" name="search" placeholder="Pesquisar escola... " value="{{request('search')}}">
            </div>
            <button class="btn" type="submit">Pesquisar</button>
        </div>
     </form>

    <!-- Tabela de Escolas -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Bairro</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($escolas as $escola)
                    @if($escola->estado == 1)
                        <tr>
                            <td>{{$escola->id}}</td>
                            <td>{{$escola->user->name}}</td>
                            <td>{{$escola->user->email}}</td>
                            <td>{{$escola->bairro->nome}}</td>
                            <td>{{$escola->telefone}}</td>
                            <td id="action">
                                <button class="btn btn-sm btn-custom action mb-1"><ion-icon name="create-outline"></ion-icon>Editar</button>
                                <form action="{{route('DeletarEscola',$escola->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-custom action mb-1"><ion-icon name="trash-outline"></ion-icon>Deletar</button>
                                </form>
                                
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Botão de Cadastrar Novo Estudante -->
    <div class="text-center mt-4">
        <a class="btn btn-custom" href="/Admin/CadastrarEscola">Cadastrar nova escola</a>
    </div>
</div>
<div id="space"></div>
@endsection