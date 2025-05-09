@extends('layouts.main')
@section('title','Bem-Vindo')
@section('content')

<div class="container mt-5">
    <div class="row g-4">
        <!-- Coluna esquerda -->
        <div class="col-md-4">
            <!-- DADOS PESSOAIS -->
            <div class="card dados-pessoais">
                <div class="profile-photo-container" id="profile-photo">
                    @if ($estudante && $estudante->foto)
                        <img src="{{ asset('storage/'.$estudante->foto) }}" alt="">
                    @else
                        <ion-icon name="camera-outline"></ion-icon>
                    @endif
                </div>
                <div>
                    <h5 class="card-title">Informações do Estudante</h5>
                    <p><strong>Nome:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Username:</strong> {{ $user->username }}</p>
                    <p><strong>Telefone:</strong> {{ $estudante->telefone }}</p>
                    <p><strong>Endereço:</strong> {{ $estudante->endereco }}</p>
                    <p><strong>Turno:</strong> {{ $turno->nome }} - {{ $turno->HoraIda }} - {{ $turno->HoraRegresso }}</p>
                    @if($rota === null)
                        <p><strong>Rota:</strong> Sem rota</p>
                    @else
                        <p><strong>Rota:</strong> {{ $rota->nome }} - {{ $rota->PontoA }} - {{ $rota->PontoB }}</p>
                    @endif
                </div>
                <a href="" class="btn editar btn-custom">Editar<ion-icon name="pencil-outline"></ion-icon></a>
            </div>

            <!-- ESCOLA -->
            <div class="card" id="dados-responsavel">
                <div class="card-header">Escola do estudante</div>
                <div class="card-body">
                    @if($escola === null)
                        <span>O usuário não está vinculado à uma escola</span>
                    @else
                        <p><strong>Nome:</strong> {{ $escola->nome }}</p>
                        <p><strong>Municipio:</strong> {{ $escola->municipio }}</p>
                        <p><strong>Bairro:</strong> {{ $escola->bairro }}</p>
                        <p><strong>Email:</strong> {{ $escola->email }}</p>
                        <p><strong>Telefone:</strong> {{ $escola->telefone }}</p>
                        <div><a href="{{ route('DesvincularEscola', $escola->id) }}" class="btn">Desvincular-se</a></div>
                    @endif
                </div>
            </div>

            <!-- RESPONSÁVEIS -->
            <div class="card" id="dados-responsavel">
                <div class="card-header">Responsáveis do estudante</div>
                <div class="card-body">
                    <div class="list-group overflow">
                        @if ($responsaveis->isEmpty())
                            <span>Nenhum Responsável adicionado</span>
                        @else
                            @foreach($responsaveis as $responsavel)
                                <a href="{{ route('InfoResponsavel', $responsavel->id) }}" class="student-link btn btn-lista d-flex justify-content-between align-items-center mb-3">
                                    <span>{{ $responsavel->nome }}</span>
                                    <span>Ver Mais</span>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna direita -->
        <div class="col-md-8">
            <div class="card outro">

                <!-- VIAGEM -->
                <div class="card">
                    <div class="card-header">Viagem Atual <ion-icon name="bus-outline"></ion-icon></div>
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

                <!-- NOTIFICAÇÕES -->
                <div class="card" id="MsgNotificacoes">
                    <div class="card-header">Notificações <ion-icon name="notifications-outline"></ion-icon></div>
                    <div class="card-body notificacoes">
                        @if($notificacoes->isEmpty())
                            <p>Nenhuma notificação no momento.</p>
                        @else
                            <ul class="list-group">
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

                <!-- HISTÓRICO -->
                <div class="card">
                    <div class="card-header">Histórico de Viagens <ion-icon name="bus-outline"></ion-icon></div>
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

                <!-- MAPA -->
                @if($viagem && in_array($viagem->estado, [1, 2]))
                <div class="card">
                    <div class="card-header">Localização em Tempo Real <ion-icon name="navigate-circle-outline"></ion-icon></div>
                    <div class="card-body">
                        <div id="map" style="height: 400px;"></div>
                    </div>
                </div>
                @endif


                <!-- ESCOLAS -->
                <div class="card" id="dados-responsavel">
                    <div class="card-header">Escolas e Conexões</div>
                    <div class="card-body">
                        <div class="list-group overflow">
                            @if ($escolas->isEmpty())
                                <span>Nenhum pedido de conexão</span>
                            @else
                                @foreach($escolas as $escola)
                                    <a href="{{ route('EscolasConexoes', $escola->id) }}" class="student-link btn btn-lista d-flex justify-content-between align-items-center mb-3">
                                        <span>{{ $escola->nome }}</span>
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

@section('scripts')

<!-- Leaflet CSS + JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Socket.IO -->
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>

<script>
    // Pegando o ID do motorista passado pelo controller
    let motoristaId = "{{ $motoristaId }}";

    // Verifica se motoristaId é válido
    if (!motoristaId || isNaN(motoristaId)) {
        console.warn("ID do motorista inválido, usando fallback");
        motoristaId = "6"; // fallback temporário
    } else {
        console.log("ID do motorista recebido:", motoristaId);
    }

    let map = L.map('map').setView([-8.8383, 13.2344], 13); // Posição inicial (Luanda)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18
    }).addTo(map);

    let marker = L.marker([-8.8383, 13.2344]).addTo(map);

    const socket = io("https://01c6-102-214-36-148.ngrok-free.app", { transports: ["websocket"] });

    socket.on("connect", () => {
        console.log("Conectado ao socket com ID:", socket.id);

        // Entrar na sala do motorista
        console.log("Entrando na sala do motorista:", motoristaId);
        socket.emit("entrarSala", motoristaId);
    });

    // ✅ Receber localização atualizada do motorista
    socket.on("atualizacao-localizacao", (data) => {
        console.log("Localização recebida:", data);
        const { latitude, longitude } = data;
        marker.setLatLng([latitude, longitude]);
        map.setView([latitude, longitude], 15);
    });

    socket.on("connect_error", (error) => {
        console.error("Erro de conexão ao socket:", error);
    });
</script>

@endsection



