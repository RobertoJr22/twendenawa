@extends('layouts.main')
@section('title', 'Informações do Responsável')
@section('content')
<div class="position-relative top-0 start-0 m-3">
    <a href="{{ url()->previous() }}" class="btn">Voltar</a>
</div>
<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="container" style="max-width: 600px;">
        <h2 class="text-center mb-4">Informações do Responsável</h2>

        <div class="card mb-3">
            <div class="card-body text-center">
                <div class="profile-photo-container" id="profile-photo" style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden; margin: 0 auto;">
                    @if ($responsavel && $responsavel->foto)
                        <img src="{{ asset('storage/' . $responsavel->foto) }}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                    @else
                        <ion-icon name="camera-outline" style="font-size: 50px; display: block; margin: auto;"></ion-icon>
                    @endif
                </div>
                
                <p><strong>Nome:</strong> {{ $responsavel->Nome ?: 'Sem dados' }}</p>
                <p><strong>email:</strong> {{ $responsavel->email?: 'Sem dados' }}</p>
                <p><strong>Data de Nascimento:</strong> {{ $responsavel->DataNascimento ?: 'Sem dados' }}</p>
                <p><strong>endereço:</strong> {{ $responsavel->endereco ?: 'Sem dados' }}</p>
                <p><strong>Telefone:</strong> {{ $responsavel->telefone ?: 'Sem dados' }}</p>

                <div class="text-center">
                    <a href="{{ route('DesfazerConexao',$responsavel->id)}}" class="btn">Desfazer Conexão</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection