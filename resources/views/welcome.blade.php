@extends('layouts.main')
@section('title','Twendenawa')
@section('content')
<div id="welcome">
    <div class="text-center">
        <h1 class="font-weight-bold">Twendenawa</h1> <!-- Tamanho grande e negrito -->
        <h3 id="subtitle-vamos">Vamos bem, vamos longe!</h3> <!-- Tamanho um pouco inferior e negrito -->
        <!-- Botão de Cadastrar-se -->
        <a href="/auth/Selecao" class="btn btn-cadastrar px-4 mt-3">Cadastrar-se</a>
        <a href="/auth/login"><h4>login</h4></a>
    </div>
</div>
@endsection
