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
            <h5>Informações do Estudante</h5>
            <p><strong>Nome:</strong>{{$viagem->Estudante}}</p>
            @php
                use Carbon\Carbon;
            @endphp
            <p><strong>Idade:</strong>{{ Carbon::parse($viagem->DataNascimento)->age }} anos</p>
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
            <p><strong>Hora de início:</strong>{{$viagem->HoraInicio}}</p>
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
                    <small class="text-muted">{{ $relatorio->created_at->diffForHumans() }}</small>
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