@extends('layouts.main')
@section('title','Cadastrar Rota')
@section('content')
<div class="container d-flex justify-content-center mt-5">
    <div class="col-md-8 col-lg-6">
        <h2 class="text-center mb-4 fs-responsive">Cadastrar Rota</h2>
        <form method="POST" action="{{route('CadastrarRota')}}">
            @csrf
            <!-- Nome -->
            <div class="mb-3">
                <label for="name" class="form-label">Nome da Rota:</label>
                <input type="text" class="form-control" id="nome" name="nome" value="{{$NomeRota}}" readonly>
            </div>

            <!-- Campo de PontoA -->
            <div class="mb-3">
                <label for="pontoA" class="form-label">Ponto A:</label>
                <input type="text" class="form-control" id="pontoA" name="pontoA" value="{{$user->name}}" readonly>
            </div>

            <!-- Campo de PontoB -->
            <div class="mb-3">
                <label for="pontoB" class="form-label">Ponto B:</label>
                <input type="text" class="form-control" id="pontoB" name="pontoB" required>
            </div>

            <!-- Botão de Ação -->
            <button type="submit" class="btn btn-custom w-100">Salvar Cadastro</button>
        </form>
    </div>
</div>
<div id="space"></div>
@endsection