
@extends('layouts.main')
@section('title','Bem-Vindo')
@section('content')

<div class="container mt-5">
        <div class="row g-4">
            <!-- Coluna esquerda (dados pessoais) -->
            <div class="col-md-4">
                <div class="card dados-pessoais text-center">
                    <!-- Foto de Perfil -->
                    <div class="profile-photo-container" id="profile-photo">
                        <!-- Ícone de Câmera -->
                        <ion-icon name="camera-outline"></ion-icon>
                        <!-- Imagem de Perfil (escondida por padrão) -->
                        <img src="" alt="Foto de Perfil">
                    </div>

                    <!-- Dados Pessoais -->
                    <div>
                        <p><strong>Nome:</strong> João Silva</p>
                        <p><strong>Email:</strong> joao.silva@example.com</p>
                        <p><strong>Telefone:</strong> +244 912 345 678</p>
                        <p><strong>Endereço:</strong> Rua Principal, nº 123, Luanda</p>
                    </div>
                    <a href="" class="btn editar">Editar<ion-icon name="pencil-outline"></ion-icon></a>
                </div>
            </div>

            <!-- Coluna direita (outro card) -->
            <div class="col-md-8">
                <div class="card outro">
                    <!-- Viagens Ativas -->
                    <div class="card">
                        <div class="card-header">
                            Viagens Ativas&nbsp; <ion-icon name="bus-outline"></ion-icon>
                        </div>
                        <div class="card-body">
                            <p><strong>Estudantes em Viagem:</strong></p>
                            <div class="list-group">
                                <a href="#" class="student-link btn btn-outline-primary d-flex justify-content-between align-items-center mb-3">
                                    <span>João Silva</span>
                                    <span>Ver Mais</span>
                                </a>
                                <a href="#" class="student-link btn btn-outline-primary d-flex justify-content-between align-items-center mb-3">
                                    <span>Maria Souza</span>
                                    <span>Ver Mais</span>
                                </a>
                                <a href="#" class="student-link btn btn-outline-primary d-flex justify-content-between align-items-center mb-3">
                                    <span>Pedro Almeida</span>
                                    <span>Ver Mais</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Notificações -->
                    <div class="card mt-3">
                        <div class="card-header">
                            Notificações&nbsp; <ion-icon name="notifications-outline"></ion-icon>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item">Estudante João Silva entrou no veículo às 07:30. <span class="text-muted">(Hoje)</span></li>
                                <li class="list-group-item">Estudante João Silva chegou ao destino às 08:15. <span class="text-muted">(Hoje)</span></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Estudantes Sob Responsabilidade -->
                    <div class="card mt-3">
                        <div class="card-header">
                            Estudantes Sob Sua Responsabilidade&nbsp;<ion-icon name="people-outline"></ion-icon>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <a href="#" class="student-link btn btn-outline-primary d-flex justify-content-between align-items-center mb-3">
                                    <span>João Silva</span>
                                    <span>Ver Mais</span>
                                </a>
                                <a href="#" class="student-link btn btn-outline-primary d-flex justify-content-between align-items-center mb-3">
                                    <span>Maria Souza</span>
                                    <span>Ver Mais</span>
                                </a>
                                <a href="#" class="student-link btn btn-outline-primary d-flex justify-content-between align-items-center mb-3">
                                    <span>Pedro Almeida</span>
                                    <span>Ver Mais</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection