@extends('layouts.main')
@section('title','Bem-Vindo')
@section('content')

<div class="container mt-5">
        <div class="row g-4">
            <!-- Coluna esquerda (dados pessoais) -->
            <div class="col-md-4">
                <!--DADOS PESSOAIS-->
                <div class="card dados-pessoais">
                    <!-- Foto de Perfil -->
                    <div class="profile-photo-container" id="profile-photo">
                        @if ($estudante && $estudante->foto)
                            <img src="{{ asset('storage/'.$estudante->foto) }}" alt="">
                        @else
                            <ion-icon name="camera-outline"></ion-icon>
                        @endif
                    </div>
                    <!-- Dados Pessoais -->
                    <div>
                        <h5 class="card-title">Informações do Estudante</h5>
                        <p><strong>Nome:</strong>{{$user->name}}</p>
                        <p><strong>Email:</strong>{{ $user->email}}</p>
                        <p><strong>Telefone:</strong>{{$estudante->telefone}}</p>
                        <p><strong>Endereço:</strong>{{$estudante->endereco}}</p>
                        <p><strong>Turno:</strong>{{$turno->nome}}-{{$turno->HoraIda}}-{{$turno->HoraRegresso}}</p>
                        @if($rota === null)
                            <p><strong>Rota:</strong>Sem rota</p>
                        @else
                            <p><strong>Rota:</strong>{{$rota->nome}}-{{$rota->PontoA}}-{{$rota->PontoB}}</p> 
                        @endif                    
                    </div>
                    <a href="" class="btn editar btn-custom">Editar<ion-icon name="pencil-outline"></ion-icon></a>
                </div>
                <!-- Instituicao de ensino -->
                <div class="card" id="dados-responsavel">
                    <div class="card-header">
                        Escola do estudante
                    </div>
                    <div class="card-body">
                        @if($escola === null)
                            <span>O usuário não está vinculado à uma escola</span>
                        @else
                            <p><strong>Nome:</strong>{{$escola->nome}}</p>
                            <p><strong>Municipio:</strong>{{$escola->municipio}}</p>
                            <p><strong>Bairro:</strong>{{$escola->bairro}}</p>
                            <p><strong>Email:</strong>{{ $escola->email}}</p>
                            <p><strong>Telefone:</strong>{{$escola->telefone}}</p>
                        @endif
                    </div>
                </div>
                <!-- Responsaveis do estudante -->
                <div class="card" id="dados-responsavel">
                    <div class="card-header">
                        Responsáveis do estudante
                    </div>
                    <div class="card-body">
                        <div class="list-group overflow">
                        @if ($responsaveis->isEmpty())
                            <span>Nenhum Responsável adicionado</span>
                        @else
                            @foreach($responsaveis as $responsavel)
                                <a href="/Estudante/DetalhesResponsavel" class="student-link btn btn-lista d-flex justify-content-between align-items-center mb-3">
                                    <span>{{ $responsavel->nome }}</span>
                                    <span>Ver Mais</span>
                                </a>
                            @endforeach
                        @endif
                        </div>
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
                        @if(!$viagem)
                            <p>Sem viagem no momento</p>
                        @else
                            <p><strong>Veículo:</strong> {{ $viagem->marca ?? 'Desconhecido' }} {{ $viagem->modelo ?? '' }} {{ $viagem->Matricula ?? 'Sem matrícula' }}</p>
                            <p><strong>Motorista:</strong> {{ $viagem->motorista ?? 'Não informado' }}</p>
                            <p><strong>Local de Partida:</strong> {{ $viagem->PontoA ?? 'Não informado' }}</p>
                            <p><strong>Local de Destino:</strong> {{ $viagem->PontoB ?? 'Não informado' }}</p>
                            <p><strong>Horário de Início:</strong> {{ $viagem->HoraIda ?? 'Não definido' }}</p>
                            <p><strong>Horário Estimado de Chegada:</strong> {{ $viagem->HoraRegresso ?? 'Não definido' }}</p>
                            <button class="btn btn-custom">Ver Detalhes</button>
                        @endif
                        </div>
                    </div>
                    <!-- Notificações Recentes -->
                    <div class="card" id="MsgNotificacoes">
                        <div class="card-header">
                            Notificações<ion-icon name="notifications-outline"></ion-icon>
                        </div>
                        <div class="card-body notificacoes">
                        @if($notificacoes->isEmpty())
                            <p>Nenhuma notificação no momento.</p>
                        @else
                            <ul class="list-group notificacoes">
                                @foreach($notificacoes as $notificacao)
                                    <li class="list-group-item">
                                        {{ $notificacao->data['mensagem'] }}<br>
                                        <small class="text-muted">{{ $notificacao->created_at->diffForHumans() }}</small>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
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