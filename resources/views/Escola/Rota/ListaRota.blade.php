@extends('layouts.main')
@section('title','Escolas')
@section('content')
<!-- Container principal da Dashboard -->
<div class="container mt-5">
    <h2 class="text-center mb-4 fs-responsive">Lista de Escolas</h2>
    
    <!-- Barra de Pesquisa -->
     <form action="{{route('ListaRota')}}" method="get">
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
                    <th>Ponto A</th>
                    <th>Ponto B</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rotas as $rota)
                    <tr>
                        <td>{{$rota->id}}</td>
                        <td>{{$rota->nome}}</td>
                        <td>{{$rota->PontoA}}</td>
                        <td>{{$rota->PontoB}}</td>
                        <td id="action">
                            <button class="btn btn-sm btn-custom action mb-1"><ion-icon name="create-outline"></ion-icon>Editar</button>
                            <button class="btn btn-sm btn-custom action mb-1"><ion-icon name="trash-outline"></ion-icon>Deletar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Botão de Cadastrar Nova Rota -->
    <div class="text-center mt-4">
        <a class="btn btn-custom" href="/Escola/Rota/CadastrarRota">Cadastrar nova Rota</a>
    </div>
</div>
<div id="space"></div>
@endsection