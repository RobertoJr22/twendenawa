@extends('layouts.main')
@section('title','Bem-Vindo')
@section('content')
<!-- Container principal -->
<div class="custom-container">
    <h2 class="text-center mb-4 fs-responsive">Pesquisar Estudante</h2>

    <!-- Barra de Pesquisa -->
    <div class="search-bar mb-4">
        <input type="text" class="form-control w-100 w-md-60" placeholder="Digite o Id do estudante...">
    </div>


    <div id="card-selecionar" class="d-flex flex-column justify-content-center align-items-center p-3">
        <div class="d-flex flex-column flex-md-row justify-content-between p-3 w-100">
            <!-- Lado esquerdo: Foto do estudante -->
            <div class="photo-container d-flex align-items-center justify-content-center mb-3 mb-md-0 col-12 col-md-4">
                <img src="https://via.placeholder.com/150" alt="Foto do Estudante" class="img-fluid">
            </div>
            <!-- Informações do estudante -->
            <div class="col-12 col-md-8">
                <h5><strong>Informações do Estudante</strong></h5>
                <p><strong>Nome:</strong> João Silva</p>
                <p><strong>Data de Nascimento:</strong> 10/05/2008</p>
                <p><strong>Telefone:</strong> +244 912 345 678</p>
                <p><strong>Responsável:</strong> Maria Silva</p>
                <p><strong>Telefone do Responsável:</strong> +244 923 456 789</p>
            </div>
        </div>

        <!-- Botões centralizados -->
        <div class="buttons-container m-3 text-center">
            <button class="btn btn-lista me-2">Adicionar</button>
            <button class="btn btn-lista">Retirar</button>
        </div>
    </div>
</div>
@endsection