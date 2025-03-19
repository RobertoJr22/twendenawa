@extends('layouts.main')
@section('title','Escolas')
@section('content')
<div class="container">
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="text-center">
            <div class="profile-photo-container d-flex justify-content-center align-items-center mx-auto" style="width:150px; height:150px;">
                @if ($SearchEscola && $SearchEscola->foto)
                <img src="{{ asset('storage/'.$SearchEscola->foto) }}" alt="">
                @else
                <ion-icon name="camera-outline"></ion-icon>
                @endif
                </div>
                <!-- Dados Pessoais -->
                <h5 class="card-title">Informações da Instituição</h5>
                <p><strong>Nome:</strong> {{$SearchEscola->nome}}</p>
                <p><strong>Telefone:</strong> {{$SearchEscola->telefone}}</p>
                <p><strong>Municipio:</strong> {{$SearchEscola->municipio}}</p>
                <p><strong>Distrito:</strong> {{$SearchEscola->distrito}}</p>
                <p><strong>Bairro:</strong> {{$SearchEscola->bairro}}</p>
                @if(!$estado || $estado->estado == 0)
                    <p class="btn btn-custom">Sem conexão</p>
                @elseif($estado->estado == 1)
                    <a href="{{route('AcaoEscolaConexao',[$SearchEscola->id,0])}}" class="btn btn-custom">Desfazer conexão</a>
                @elseif($estado->estado == 2)
                    <a href="{{route('AcaoEscolaConexao',[$SearchEscola->id,'aceitar'])}}" class="btn btn-custom">Aceitar conexão</a>
                    <a href="{{route('AcaoEscolaConexao',[$SearchEscola->id,'negar'])}}" class="btn btn-custom">Negar conexão</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection