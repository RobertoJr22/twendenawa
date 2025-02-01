@extends('layouts.main')
@section('title','Bem-Vindo')
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
                        <img src="" >
                    </div>

                    <!-- Dados Pessoais -->
                    <div>
                        <h5 class="card-title">Informações do Estudante</h5>
                        <p><strong>Nome:</strong>{{ $helper->DadosUsuario('name') }}</p>
                        <p><strong>Email:</strong> {{ $helper->DadosUsuario('email') }}</p>
                        <p><strong>Telefone:</strong> +244 912 345 678</p>
                        <p><strong>Endereço:</strong> Rua Principal, nº 123, Luanda</p>
                    </div>
                    <a href="" class="btn editar btn-custom">Editar<ion-icon name="pencil-outline"></ion-icon></a>
                </div>
                <!-- Dados responsavel -->
                <div class="card" id="dados-responsavel">
                    <div class="card-header">
                        Dados do Responsável
                    </div>
                    <div class="card-body">
                        <p><ion-icon name="person-outline"></ion-icon>:&nbsp; João Silva</p>
                        <p><ion-icon name="mail-outline"></ion-icon>:&nbsp; joao.silva@example.com</p>
                        <p><ion-icon name="call-outline"></ion-icon>:&nbsp; +244 912 345 678</p>
                        <p><ion-icon name="home-outline"></ion-icon>:&nbsp; Rua Principal, nº 123, Luanda</p>
                    </div>
                </div>
            </div>

            <!-- Coluna direita (outro card) -->
            <div class="col-md-8">
                <div class="card outro">
                    <!-- Viagem Atual -->
                    <div class="card">
                        <div class="card-header">
                            Viagem Atual <ion-icon name="bus-outline"></ion-icon>
                        </div>
                        <div class="card-body">
                            <p><strong>Veículo:</strong> Minibus Azul</p>
                            <p><strong>Motorista:</strong> João Silva</p>
                            <p><strong>Local de Partida:</strong> Escola Técnica</p>
                            <p><strong>Local de Destino:</strong> Rua Principal</p>
                            <p><strong>Horário de Início:</strong> 07:30</p>
                            <p><strong>Horário Estimado de Chegada:</strong> 08:15</p>
                            <button class="btn btn-custom">Ver Detalhes</button>
                        </div>
                    </div>
                        <!-- Notificações Recentes -->
                        <div class="card">
                        <div class="card-header">
                            Notificações Recentes <ion-icon name="notifications-outline"></ion-icon>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item">Viagem iniciada às 07:30. <span class="text-muted">(Hoje)</span></li>
                                <li class="list-group-item">Você desembarcou às 08:15. <span class="text-muted">(Ontem)</span></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Histórico de Viagens -->
                    <div class="card">
                        <div class="card-header">
                            Histórico de Viagens <ion-icon name="bus-outline"></ion-icon>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item">Viagem concluída em 27/11/2024. Motorista: João Silva.</li>
                                <li class="list-group-item">Viagem concluída em 26/11/2024. Motorista: Pedro Alves.</li>
                            </ul>
                            <div class="text-center mt-3">
                                <button class="btn btn-custom">Ver Histórico Completo</button>
                            </div>
                        </div>
                    </div>
                    <!-- Mapa Interativo -->
                    <div class="card">
                        <div class="card-header">
                            Localização em Tempo Real <ion-icon name="navigate-circle-outline"></ion-icon>
                        </div>
                        <div class="card-body">
                            <div class="map-container">
                                Mapa interativo aqui (em desenvolvimento) <ion-icon name="map-outline"></ion-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection