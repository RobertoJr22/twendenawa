@extends('layouts.main')
@section('title','Twendenawa')
@section('content')
<!-- Container centralizado com botões largos -->
<div class="container d-flex flex-column align-items-center mt-5">
    <h2 class="text-center mb-4 fs-responsive">Selecione o tipo de usuário</h2>
     
    <!-- Botão Estudantes -->
    <a href="/auth/EstudanteRegister" class="btn-custom mb-3 btn-main-dash"><ion-icon name="school-outline"></ion-icon>Estudante</a>

    <!-- Botão Responsáveis -->
    <a href="/auth/ResponsavelRegister" class="btn-custom mb-3 btn-main-dash"><ion-icon name="people-outline"></ion-icon>Responsável</a>
</div>
<div id="space"></div>
@endsection