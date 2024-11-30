@extends('layouts.main')
@section('title','Dashboard')
@section('content')
    <!-- Container centralizado com botões largos -->
    <div class="container d-flex flex-column align-items-center mt-5">
        <h2 class="text-center mb-4">Central de controlo</h2>
        
        <!-- Botão Estudantes -->
        <a href="/Dashboard/Estudante" class="btn-custom mb-3">Estudantes</a>

        <!-- Botão Responsáveis -->
        <a href="/Dashboard/Responsavel" class="btn-custom mb-3">Responsáveis</a>

        <!-- Botão Motoristas -->
        <a href="/Dashboard/Motorista" class="btn-custom mb-3">Motoristas</a>

        <!-- Botão Viaturas -->
        <a href="/Dashboard/Viatura" class="btn-custom mb-3">Viaturas</a>
    </div>
    <div id="space"></div>
@endsection