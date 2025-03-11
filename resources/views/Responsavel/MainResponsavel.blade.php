
@extends('layouts.main')
@section('title','Pagina Inicial')
@section('content')

<div class="container mt-5">
        <div class="row g-4">
            <!-- Coluna esquerda (dados pessoais) -->
            <div class="col-md-4">
                <div class="card dados-pessoais">
                    <!-- Foto de Perfil -->
                    <div class="profile-photo-container" id="profile-photo">
                        @if ($responsavel && $responsavel->foto)
                            <img src="{{ asset('storage/'.$responsavel->foto) }}" alt="">
                        @else
                            <ion-icon name="camera-outline"></ion-icon>
                        @endif
                    </div>

                    <!-- Dados Pessoais -->
                    <div>
                        <h5 class="card-title">Informações do Responsável</h5>
                        <p><strong>Nome:</strong> {{ $user->name }}</p>
                        <p><strong>Username:</strong>{{ $user->username}}</p>
                        <p><strong>Email:</strong> {{$user->email}}</p>
                        <p><strong>Telefone:</strong> {{$responsavel ? $responsavel->telefone : 'Não informado'}}</p>
                        <p><strong>Endereço:</strong> {{$responsavel ? $responsavel->endereco : 'Não informado'}}</p>
                    </div>
                    <a href="" class="btn editar .btn-custom">Editar<ion-icon name="pencil-outline"></ion-icon></a>
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
                        <div class="card-body notificacoes">
                            <p><strong>Estudantes em Viagem:</strong></p>
                            <div class="list-group overflow">
                                @if($viagens->isEmpty())
                                    <span>Sem estudantes em viagens ativas</span>
                                @else
                                    @foreach($viagens as $viagem)
                                    <a href="{{route('DetalhesViagem',$viagens->IdEstudante)}}" class="student-link btn btn-lista d-flex justify-content-between align-items-center mb-3">
                                        <span>{{$viagem->NomeEstudante}}</span>
                                        <span>Ver Mais</span>
                                    </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Seção de Notificações -->
                    <div class="card mt-3" id="MsgNotificacoes">
                            <div class="card-header">
                                Notificações&nbsp; <ion-icon name="notifications-outline"></ion-icon>
                            </div>
                            <div class="card-body">
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

                    <!-- Estudantes Sob Responsabilidade -->
                    <div class="card mt-3">
                        <div class="card-header">
                            Estudantes de sua tutela&nbsp;<ion-icon name="people-outline"></ion-icon>
                        </div>
                        <div class="card-body">
                            <div class="list-group overflow">
                                @if($estudantes->isEmpty())
                                    <span>Sem estudantes associados a ti</span>
                                @else
                                    @foreach($estudantes as $estudante)
                                        <a href="{{route('InfoEstudante', $estudante->id)}}" class="student-link btn btn-lista d-flex justify-content-between align-items-center mb-3">
                                            <span>{{$estudante->nome}}</span>
                                            <span>Ver Mais</span>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection