@extends('layouts.main')
@section('title','Dashboard')
@section('content')
    <!-- Container centralizado com botões largos -->
    <div class="container d-flex flex-column align-items-center mt-5">
        <h2 class="text-center mb-4 fs-responsive">Central de controlo</h2>
        
        <!-- Botão Estudantes -->
        <a href="/Dashboard/Estudante" class="btn-custom mb-3 btn-main-dash">Estudantes</a>

        <!-- Botão Responsáveis -->
        <a href="/Dashboard/Responsavel" class="btn-custom mb-3 btn-main-dash">Responsáveis</a>

        <!-- Botão Motoristas -->
        <a href="/Dashboard/Motorista" class="btn-custom mb-3 btn-main-dash">Motoristas</a>

        <!-- Botão Viaturas -->
        <a href="/Dashboard/Viatura" class="btn-custom mb-3 btn-main-dash">Viaturas</a>
    </div>
    <div id="space"></div>
@endsection