@extends('layouts.main')
@section('title','Cadastrar Motorista')
@section('content')

<div class="container mt-5">
    <div class="row g-4">
        <!-- Coluna esquerda (dados pessoais) -->
        <div class="col-md-4">
            <div class="card dados-pessoais">
                <!-- Foto de Perfil -->
                <div class="profile-photo-container" id="profile-photo">
                    <!-- Ícone de Câmera -->
                    <ion-icon name="camera-outline"></ion-icon>
                    <!-- Imagem de Perfil (escondida por padrão) -->
                    <img src="" alt="Foto de Perfil">
                </div>

                <!-- Dados Pessoais -->
                <div>
                    <h5 class="card-title">Informações do Motorista</h5>
                    <p><strong>Nome:</strong> {{$helper->DadosUsuario('name')}}</p>
                    <p><strong>Veículo:</strong> Minibus Azul</p>
                    <p><strong>Telefone:</strong> +244 912 345 678</p>
                    <p><strong>Placa do Veículo:</strong> ABC1234</p>
                </div>
                <a href="" class="btn editar .btn-custom">Editar<ion-icon name="pencil-outline"></ion-icon></a>
            </div>
        </div>

        <!-- Coluna direita (outro card) -->
        <div class="col-md-8">
            <div class="card outro">
                <!-- Mapa Interativo -->
                <div class="card mt-3">
                    <div class="card-header">
                        Localização em Tempo Real <ion-icon name="navigate-circle-outline"></ion-icon>
                    </div>
                    <div class="card-body">
                        <div class="map-container">
                            Mapa interativo (em desenvolvimento)
                        </div>
                        <!-- Botão para iniciar/pausar a viagem -->
                        <div class="text-center mt-3">
                            <button class="btn viagem">Iniciar Viagem <ion-icon name="play-outline"></ion-icon></button>
                            <button class="btn viagem">Pausar Viagem <ion-icon name="pause-outline"></ion-icon></button>
                            <button class="btn viagem">Pausar Viagem <ion-icon name="stop-outline"></ion-icon></button>
                        </div>
                    </div>                   
                </div>
                <!-- Viagens Ativas -->
                <div class="card">
                    <div class="card-header">
                        Viagens Ativas <ion-icon name="bus-outline"></ion-icon>
                    </div>
                    <div class="card-body">
                        <p><strong>Estudantes a bordo:</strong></p>
                        <div class="list-group">
                            <a href="#" id="btn-lista" class="btn d-flex justify-content-between align-items-center mb-3">
                                <span>João Silva</span>
                                <span>Ver Mais</span>
                            </a>
                            <a href="#" id="btn-lista" class="btn  d-flex justify-content-between align-items-center mb-3">
                                <span>Maria Souza</span>
                                <span>Ver Mais</span>
                            </a>
                        </div>
                        <!-- Botão de Voltar -->
                        <div class="text-center">
                            <a href="/Estudante/SelecaoEstudante" class="btn">Adicionar</a>
                        </div>
                    </div>
                </div>

                <!-- Relatório de Eventualidades -->
                <div class="card mt-3">
                    <div class="card-header">
                        Relatório de Eventualidades <ion-icon name="alert-circle-outline"></ion-icon>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" rows="4" placeholder="Informe qualquer eventualidade na viagem..."></textarea>
                        <button class="btn .btn-custom mt-3">Enviar Relatório</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection