@extends('layouts.main')
@section('title','Bem-Vindo')
@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4 fs-responsive">Detalhes da Viagem</h2>

    <!-- Informações do Estudante -->
    <div class="card">
        <div class="card-body">
            <h5>Informações do Estudante</h5>
            <p><strong>Nome:</strong> João Silva</p>
            <p><strong>Idade:</strong> 16 anos</p>
            <p><strong>Instituição:</strong> Escola Técnica de Luanda</p>
            <p><strong>Contato dos Responsáveis:</strong> +244 912 345 678</p>
        </div>
        <div class="card-body">
            <h5>Informações do Motorista</h5>
            <p><strong>Nome:</strong> Carlos Almeida</p>
            <p><strong>Telefone:</strong> +244 923 456 789</p>
            <p><strong>Experiência:</strong> 5 anos como motorista escolar</p>
        </div>
        <div class="card-body">
            <h5>Informações da Viagem</h5>
            <p><strong>Hora de Início:</strong> 07:30</p>
            <p><strong>Status:</strong> Em Andamento</p>
            <p><strong>Destino:</strong> Escola Técnica de Luanda</p>
        </div>
        <div class="card-body">
            <h5>Informações do Veículo</h5>
            <p><strong>Modelo:</strong> Toyota Hiace</p>
            <p><strong>Cor:</strong> Branco</p>
            <p><strong>Placa:</strong> ABC1234</p>
            <p><strong>Capacidade:</strong> 15 passageiros</p>
        </div>
        <div class="card-body">
            <h5>Relatório de Eventualidades</h5>
            <p id="relatorio-viagem">Houve um atraso de 15 minutos devido a trânsito intenso na Av. Principal.</p>
            <p><strong>Data:</strong> 30/11/2024</p>
            <p><strong>Descrição Adicional:</strong> Nenhum problema mecânico ou de segurança foi identificado.</p>
        </div>
        <!-- Botão de Voltar -->
        <div class="text-center">
            <button class="btn">Voltar</button>
        </div>
        <div id="space"></div>
    </div>
</div>

@endsection