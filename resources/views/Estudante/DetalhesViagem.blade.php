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
            <p><strong>Idade:</strong> (calcula a idade com base no campo DataNascimento da tabela estudante)</p>
            <p><strong>Instituição:</strong> Escola Técnica de Luanda</p>
            <p><strong>Contato dos Responsáveis:</strong> +244 912 345 678</p>
        </div>
        <div class="card-body">
            <h5>Informações do Motorista</h5>
            <p><strong>Nome:</strong> Carlos Almeida</p>
            <p><strong>Telefone:</strong> +244 923 456 789</p>
        </div>
        <div class="card-body">
            <h5>Informações da Viagem</h5>
            <p><strong>Hora de Início:</strong> 07:30</p>
            <p><strong>Status:</strong> Em Andamento (coloca no caso do estado na tabela viagems ser igual a 1)</p>
            <p><strong>Destino:</strong> Escola Técnica de Luanda</p>
        </div>
        <div class="card-body">
            <h5>Informações do Veículo</h5>
            <p><strong>Modelo:</strong> Toyota Hiace</p>
            <p><strong>Placa:</strong> ABC1234</p>
            <p><strong>Capacidade:</strong>(campo capacidade na tabela veiculo)</p>
        </div>
        <div class="card-body">
            <h5>Relatório de Eventualidades</h5>
            (aqui acho que vais precisar trazer todos os relatorios na tabela dados_viagems onde o viagems_id e igual ao id da t14)
            <p id="relatorio-viagem">Houve um atraso de 15 minutos devido a trânsito intenso na Av. Principal.</p>
            <p><strong>Data:</strong> 30/11/2024</p>
        </div>
        <!-- Botão de Voltar -->
        <div class="text-center">
            <button class="btn">Voltar</button>
        </div>
        <div id="space"></div>
    </div>
</div>

@endsection