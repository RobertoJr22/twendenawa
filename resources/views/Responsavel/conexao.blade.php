@extends('layouts.main')
@section('title','Conexões')
@section('content')
<div class="container mt-5">
    <!-- Seção 1: Barra de Pesquisa -->
    <form action="" method="get">
        @csrf
        <div class="row justify-content-center mb-4">
            <div class="col-md-6 col-sm-8 col-10">
                <input type="text" class="form-control" name="search" placeholder="Pesquisar pelo id..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" type="submit">Pesquisar</button>
            </div>
        </div>
        <input type="hidden" value="{{auth()->user()->tipo_usuario_id}}" name="TipoUsuario">
    </form>

    <!-- Seção 2 e 3: Lista de Nomes e Resultados (Invertidos) -->
    <div class="row g-4">
        <!-- Seção 1: Resultados da Pesquisa (Agora na esquerda, centralizados) -->
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <div class="card text-center w-100 p-4">
                <div class="card-body">
                    @if(auth()->user()->tipo_usuario_id == 4)
                        @if(!$SearchEstudante)
                            <p>usuário não encontrado</p>
                        @else
                            <!-- Foto de Perfil (Mantendo tamanho 150x150px e centralizada) -->
                            <div  class="profile-photo-container d-flex justify-content-center align-items-center mx-auto" style="width:150px; height:150px;">
                                @if ($SearchEstudante && $SearchEstudante->foto)
                                    <img src="{{ asset('storage/'.$SearchEstudante->foto) }}" alt="">
                                @else
                                    <ion-icon name="camera-outline"></ion-icon>
                                @endif
                            </div>
                            <!-- Dados Pessoais -->
                            <h5 class="card-title">Informações do Estudante</h5>
                            <p><strong>Nome:</strong>{{$SearchEstudante->nome}}</p>
                            <p><strong>Telefone:</strong>{{$SearchEstudante->telefone}}</p>
                            @if(!$estado || $estado->estado == 0)
                                <a href="{{route('AcaoConexao',[$SearchEstudante->id,0,auth()->user()->tipo_usuario_id])}}" class="btn btn-custom">Pedir conexão</a>
                            @elseif($estado->estado == 1)
                                <a href="{{route('AcaoConexao',[$SearchEstudante->id,1,auth()->user()->tipo_usuario_id])}}" class="btn btn-custom">Desfazer conexão</a>
                            @elseif($estado->estado == 4)
                                <a href="{{route('AcaoConexao',[$SearchEstudante->id,2,auth()->user()->tipo_usuario_id])}}" class="btn btn-custom">Aceitar conexão</a>
                                <a href="{{route('AcaoConexao',[$SearchEstudante->id,3,auth()->user()->tipo_usuario_id])}}" class="btn btn-custom">Negar conexão</a>
                            @elseif($estado->estado == 2)
                                <a href="{{route('AcaoConexao',[$SearchEstudante->id,4,auth()->user()->tipo_usuario_id])}}" class="btn btn-custom">Cancelar pedido</a>
                            @endif
                        @endif                    
                    @elseif(auth()->user()->tipo_usuario_id == 2)
                        @if(!$SearchResponsavel)
                            <p>usuário não encontrado</p>
                        @else
                            <!-- Foto de Perfil (Mantendo tamanho 150x150px e centralizada) -->
                            <div  class="profile-photo-container d-flex justify-content-center align-items-center mx-auto" style="width:150px; height:150px;">
                                @if ($SearchResponsavel && $SearchResponsavel->foto)
                                    <img src="{{ asset('storage/'.$SearchResponsavel->foto) }}" alt="">
                                @else
                                    <ion-icon name="camera-outline"></ion-icon>
                                @endif
                            </div>
                            <!-- Dados Pessoais -->
                            <h5 class="card-title">Informações do Responsável</h5>
                            <p><strong>Nome:</strong>{{$SearchResponsavel->nome}}</p>
                            <p><strong>Telefone:</strong>{{$SearchResponsavel->telefone}}</p>
                            @if(!$estado || $estado->estado == 0)
                                <a href="{{route('AcaoConexao',[$SearchResponsavel->id,0,auth()->user()->tipo_usuario_id])}}" class="btn btn-custom">Pedir conexão</a>
                            @elseif($estado->estado == 1)
                                <a href="{{route('AcaoConexao',[$SearchResponsavel->id,1,auth()->user()->tipo_usuario_id])}}" class="btn btn-custom">Desfazer conexão</a>
                            @elseif($estado->estado == 2)
                                <a href="{{route('AcaoConexao',[$SearchResponsavel->id,2,auth()->user()->tipo_usuario_id])}}" class="btn btn-custom">Aceitar conexão</a>
                                <a href="{{route('AcaoConexao',[$SearchResponsavel->id,3,auth()->user()->tipo_usuario_id])}}" class="btn btn-custom">Negar conexão</a>
                            @elseif($estado->estado == 4)
                                <a href="{{route('AcaoConexao',[$SearchResponsavel->id,4,auth()->user()->tipo_usuario_id])}}" class="btn btn-custom">Cancelar pedido</a>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Seção 2: Conexões Pendentes (Agora na direita) -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Pedidos de conexão
                </div>
                <div class="card-body">
                    @if(auth()->user()->tipo_usuario_id == 4)
                        <div class="list-group">
                            @if($estudantes->isEmpty())
                                <p>Sem pedidos de conexão</p>
                            @else
                                @foreach($estudantes as $estudante)
                                    <a href="" class="student-link btn btn-lista d-flex justify-content-between align-items-center mb-3">
                                        <span>{{$estudante->nome}}</span>
                                        <span>Ver Mais</span>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    @elseif(auth()->user()->tipo_usuario_id == 2)
                        <div class="list-group">
                            @if($responsaveis->isEmpty())
                                <p>Sem pedidos de conexão</p>
                            @else
                                @foreach($responsaveis as $responsavel)
                                    <a href="" class="student-link btn btn-lista d-flex justify-content-between align-items-center mb-3">
                                        <span>{{$responsavel->nome}}</span>
                                        <span>Ver Mais</span>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection