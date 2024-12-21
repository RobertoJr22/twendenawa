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


    <div class="card dados-pessoais">
        <!-- Foto de Perfil -->
        <div class="profile-photo-container" id="profile-photo">
            <!-- Ícone de Câmera -->
            <ion-icon name="camera-outline"></ion-icon>
            <!-- Imagem de Perfil (escondida por padrão) -->
            <img src="" alt="Foto de Perfil">
        </div>

        <!-- Dados Pessoais -->
        <div class="informacao-usuario">
            <h5 class="card-title">Informações do Estudante</h5>
            <p class="dados-usuario"><strong>Nome:</strong>{{ $helper->DadosUsuario('name') }}</p>
            <p class="dados-usuario"><strong>Email:</strong> {{ $helper->DadosUsuario('email') }}</p>
            <p class="dados-usuario"><strong>Telefone:</strong> +244 912 345 678</p>
            <p class="dados-usuario"><strong>Endereço:</strong> Rua Principal, nº 123, Luanda</p>
        </div>
        <div class="btn-SelecaoEstudante">
            <a href="" class="btn editar btn-custom dados-usuario">Adicionar<ion-icon name="add-circle-outline"></ion-icon></a>
            <a href="" class="btn editar btn-custom dados-usuario">Remover<ion-icon name="remove-circle-outline"></ion-icon></a>
        </div>
    </div>
</div>
@endsection