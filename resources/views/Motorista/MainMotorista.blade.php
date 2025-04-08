@extends('layouts.main')
@section('title','Pagina inicial')
@section('content')

<div class="container mt-5">
    <div class="row g-4">
        <!-- Coluna esquerda (dados pessoais) -->
        <div class="col-md-4">
            <div class="card dados-pessoais">
                <!-- Foto de Perfil -->
                <div class="profile-photo-container" id="profile-photo">
                    @if ($motorista && $motorista->foto)
                        <img src="{{ asset('storage/'.$motorista->foto) }}" alt="Foto do Motorista">
                    @else
                        <ion-icon name="camera-outline"></ion-icon>
                    @endif
                </div>
                <!-- Dados Pessoais -->
                <div>
                    <h5 class="card-title">Informações do Motorista</h5>
                    <p><strong>Nome:</strong> {{$user->name}}</p>
                    <p><strong>Username:</strong> {{ $user->username}}</p>
                    <p><strong>DataNascimento:</strong> {{$motorista->DataNascimento}}</p>
                    <p><strong>Telefone:</strong> {{$motorista->telefone}}</p>
                    <p><strong>Numero da carta:</strong> {{$carteira->NumeroCarta}}</p>
                    <p><strong>Turno:</strong> {{ $turno ? $turno->nome . '-' . $turno->HoraIda . '-' . $turno->HoraRegresso : 'Sem informação' }}</p>
                    <p><strong>BI:</strong> {{$motorista->BI}}</p>
                </div>
                <a href="" class="btn editar btn-custom">Editar <ion-icon name="pencil-outline"></ion-icon></a>
            </div>
            
            <!-- Informacao do veiculo -->
            @if($dados)
            <div class="card dados-pessoais" id="InformacaoVeiculo">
                <div>
                    <h5 class="card-title">Informações do veiculo</h5>
                    <p><strong>Escola:</strong> {{$dados->veiculo->escola->user->name}}</p>
                    <p><strong>Marca:</strong> {{$dados->veiculo->modelo->marcas->nome}}</p>
                    <p><strong>Modelo:</strong> {{$dados->veiculo->modelo->nome}}</p>
                    <p><strong>Matrícula:</strong> {{$dados->veiculo->Matricula}}</p>
                    <p><strong>VIN do Veículo:</strong> {{$dados->veiculo->VIN}}</p>
                    <p><strong>Capacidade:</strong> {{$dados->veiculo->capacidade}}</p>
                    <p><strong>Rota:</strong> {{ $dados->rota ? $dados->rota->nome . '-' . $dados->rota->PontoA . '-' . $dados->rota->PontoB : 'Sem rota' }}</p>
                </div>
            </div>
            @else
            <div class="card dados-pessoais" id="InformacaoVeiculo">
                <div>
                    <h5 class="card-title">Informações do veiculo</h5>
                    <h6>Sem dados associados</h6>
                </div>
            </div>
            @endif
            
            <!-- Instituição de ensino -->
            <div class="card" id="dados-responsavel">
                <div class="card-header">
                    Informação da instituição
                </div>
                <div class="card-body">
                    @if($escola === null)
                        <span>O usuário não está vinculado à uma instituição</span>
                    @else
                        <p><strong>Nome:</strong> {{$escola->nome}}</p>
                        <p><strong>Município:</strong> {{$escola->municipio}}</p>
                        <p><strong>Bairro:</strong> {{$escola->bairro}}</p>
                        <p><strong>Email:</strong> {{ $escola->email}}</p>
                        <p><strong>Telefone:</strong> {{$escola->telefone}}</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Coluna direita (mapa, estudantes a bordo e relatório) -->
        <div class="col-md-8">
            <div class="card outro">
                <!-- Mapa Interativo -->
                <div class="card mt-3">
                    <div class="card-header">
                        Localização em Tempo Real <ion-icon name="navigate-circle-outline"></ion-icon>
                    </div>
                    <div class="card-body">
                        <div id="map" class="map-container" style="height: 400px;"></div>
                        <!-- Botão para iniciar/pausar a viagem -->
                        <div class="text-center mt-3">
                            <button class="btn viagem" id="iniciar-viagem">Iniciar Viagem <ion-icon name="play-outline"></ion-icon></button>
                            <button class="btn viagem" id="parar-viagem">Parar Viagem <ion-icon name="stop-outline"></ion-icon></button>
                        </div>
                    </div>                   
                </div>
                
                <!-- Estudantes a bordo -->
                <div class="card">
                    <div class="card-header">
                        Estudantes a bordo <ion-icon name="bus-outline"></ion-icon>
                    </div>
                    <div class="card-body">
                        <div class="list-group notificacoes">
                            @if($aBordo->isEmpty())
                                <p>Sem estudantes a bordo</p>
                            @else
                                @foreach($aBordo as $estudante)
                                    <a href="#" id="btn-lista" class="btn d-flex justify-content-between align-items-center mb-3">
                                        <span>{{$estudante->nome}}</span>
                                        <span>Ver Mais</span>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                        <!-- Botão de Adicionar estudante -->
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
                        <form action="{{route('EnviarRelatorio')}}" method="post">
                            @csrf
                            <textarea class="form-control" name="relatorio" rows="4" placeholder="Informe qualquer eventualidade na viagem..."></textarea>
                            <button type="submit" class="btn btn-custom mt-3">Enviar Relatório</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <!-- Leaflet CSS e JS -->  
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />  
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>  
    
    <!-- Socket.IO -->
    <script src="https://cdn.socket.io/4.4.0/socket.io.min.js"></script>

    <script>
        let map;
        let marker;

        document.addEventListener("DOMContentLoaded", function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    iniciarMapa(latitude, longitude);
                }, function() {
                    iniciarMapa(-8.839988, 13.289437); // Posição padrão
                }, { enableHighAccuracy: true });
            } else {
                iniciarMapa(-8.839988, 13.289437);
            }
        });

        function iniciarMapa(latitude, longitude) {
            map = L.map('map').setView([latitude, longitude], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Criar marcador inicial
            marker = L.marker([latitude, longitude]).addTo(map)
                .bindPopup("<b>Posição Atual</b>")
                .openPopup();

            //const socket = io('http://localhost:6001');

            const socket = io("https://7b30-102-214-36-184.ngrok-free.app", {
                transports: ["websocket"]
            });


            socket.on("connect", () => {
                console.log("✅ Conectado ao servidor Socket.IO com ID:", socket.id);
            });

            navigator.geolocation.watchPosition((position) => {
                const localizacao = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                };

                console.log("📍 Enviando localização:", localizacao);
                socket.emit("localizacao", localizacao);

                // Atualizar marcador no mapa
                if (marker) {
                    marker.setLatLng([localizacao.latitude, localizacao.longitude]);
                    map.setView([localizacao.latitude, localizacao.longitude]);
                }
            });

            socket.on("atualizacao-localizacao", (data) => {
                console.log("📡 Atualização recebida:", data);
            });
        }
    </script>
@endsection

