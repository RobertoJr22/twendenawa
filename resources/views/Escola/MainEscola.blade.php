@extends('layouts.main')
@section('title','Dashboard')
@section('content')
<!-- Container centralizado com botões largos -->
<div class="container d-flex flex-column align-items-center mt-5">
    <h2 class="text-center mb-4 fs-responsive">Central de controlo</h2>
    
    <!-- Botão Estudantes -->
    <a href="/Escola/Estudante" class="btn-custom mb-3 btn-main-dash"><ion-icon name="school-outline"></ion-icon>Estudantes</a>

    <!-- Botão Responsáveis -->
    <a href="/Escola/Responsavel" class="btn-custom mb-3 btn-main-dash"><ion-icon name="people-outline"></ion-icon>Responsáveis</a>

    <!-- Botão Motoristas -->
    <a href="/Escola/Motorista" class="btn-custom mb-3 btn-main-dash"><ion-icon name="speedometer-outline"></ion-icon>Motoristas</a>

    <!-- Botão Viaturas -->
    <a href="/Escola/Viatura" class="btn-custom mb-3 btn-main-dash"><ion-icon name="bus-outline"></ion-icon>Viaturas</a>

    <!-- Botão Rotas -->
    <a href="#" class="btn-custom mb-3 btn-main-dash"><ion-icon name="share-social-outline"></ion-icon>Rotas</a>
   
</div>
<div id="space"></div>
@endsection