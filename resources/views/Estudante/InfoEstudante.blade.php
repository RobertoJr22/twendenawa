@extends('layouts.main')
@section('title','Bem-Vindo')
@section('content')

<div class="container mt-5">
    <h2 class="text-center mb-4 fs-responsive">Informações do Estudante</h2>

    <!-- Informações do Estudante -->
    <div class="card">
        <div class="card-body">
            <p><strong>Nome:</strong> João Silva</p>
            <p><strong>Escola:</strong> Escola Técnica de Luanda</p>
            <p><strong>Data de Nascimento:</strong> 10/05/2008</p>
            <p><strong>Turma:</strong> A</p>
            <p><strong>Classe:</strong> 12ª</p>
            <p><strong>Telefone:</strong> +244 912 345 678</p>
            <p><strong>Nome do Responsável:</strong> Maria Silva</p>
            <p><strong>Telefone do Responsável:</strong> +244 923 456 789</p>
        </div>
        <!-- Botão de Voltar -->
        <div class="text-center">
            <a class="btn">Voltar</a>
        </div>
    </div>
</div>

@endsection