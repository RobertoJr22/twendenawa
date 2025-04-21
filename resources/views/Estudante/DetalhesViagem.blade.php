@extends('layouts.main')
@section('title','Bem-Vindo')
@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4 fs-responsive">Detalhes da Viagem</h2>

    @if(!$viagem)
        <p>A viagem ja terminou... </p>
    @else
    <!-- Informações do Estudante -->
    <div class="card">
        <div class="card-body">
            <h5>Localização em Tempo Real</h5>
            <div id="map" style="height: 400px;"></div>
        </div>
        <div class="card-body">
            <h5>Informações do Estudante</h5>
            <p><strong>Nome:</strong>{{$viagem->Estudante}}</p>
            <p><strong>Idade:</strong>{{ \Carbon\Carbon::parse($viagem->DataNascimento)->age }} anos</p>
            <p><strong>Instituição:</strong> {{$viagem->Escola}}</p>
        </div>
        <div class="card-body">
            <h5>Informações do Motorista</h5>
            <p><strong>Nome:</strong>{{$viagem->Motorista}}</p>
            <p><strong>Telefone:</strong>{{$viagem->TelMotorista}}</p>
        </div>
        <div class="card-body">
            <h5>Informações da Viagem</h5>
            <p><strong>Rota:</strong>{{$viagem->rota}}-{{$viagem->PontoA}}-{{$viagem->PontoB}}</p>
            <p><strong>Turno:</strong>{{$viagem->Turno}}</p>
            <p><strong>Hora de início:</strong>{{$viagem->HoraIda}}</p>
            <p><strong>Hora de regresso:</strong>{{$viagem->HoraRegresso}}</p>
        </div>
        <div class="card-body">
            <h5>Informações do Veículo</h5>
            <p><strong>Veículo:</strong> {{$viagem->marca}}-{{$viagem->modelo}}</p>
            <p><strong>Matrícula:</strong>{{$viagem->Matricula}}</p>
            <p><strong>Capacidade:</strong>{{$viagem->capacidade}}</p>
        </div>
        <div class="card-body">
            <h5>Relatório de Eventualidades</h5>
            @if($relatorios->isEmpty())
                <p>Sem relatórios no momento...</p>
            @else
                @foreach($relatorios as $relatorio)
                    <p id="relatorio-viagem">{{$relatorio->relatorio}}</p>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($relatorio->created_at)->diffForHumans() }}</small>
                @endforeach
            @endif
        </div>

        <!-- Botão de Voltar -->
        <div class="text-center">
            <a href="url()->previous()" class="btn">Voltar</a>
        </div>
        <div id="space"></div>
    </div>
    @endif
</div>
@endsection
@section('scripts')

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Socket.IO -->
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>

<script>
    // ID do motorista passado pelo controller (certifique-se que foi passado)
    const motoristaId = "{{ $viagem->motoristas_id ?? '' }}";

    if (motoristaId) {
        const map = L.map('map').setView([-8.8383, 13.2344], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18
        }).addTo(map);

        const marker = L.marker([-8.8383, 13.2344]).addTo(map);

        const socket = io("https://2e15-102-218-85-229.ngrok-free.app", { transports: ["websocket"] });

        socket.on("connect", () => {
            console.log("Conectado ao Socket.IO como responsável.");
            socket.emit("entrarSala", motoristaId);
        });

        socket.on("atualizacao-localizacao", (data) => {
            const { latitude, longitude } = data;
            marker.setLatLng([latitude, longitude]);
            map.setView([latitude, longitude], 15);
        });
    } else {
        console.warn("Motorista não encontrado para esta viagem.");
    }
</script>

@endsection
