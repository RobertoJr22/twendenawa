@extends('layouts.main')
@section('title', 'Informações do Estudante')
@section('content')
<div class="position-relative top-0 start-0 m-3">
    <a href="{{ url()->previous() }}" class="btn">Voltar</a>
</div>
<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="container" style="max-width: 600px;">
        <h2 class="text-center mb-4">Informações do Estudante</h2>

        @foreach($estudantes as $estudante)
        <div class="card mb-3">
            <div class="card-body text-center">
                <div class="mb-3">
                    @if (!empty($estudante->foto))
                        <img src="{{ asset('storage/' . $estudante->foto) }}" alt="Foto do Estudante" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <ion-icon name="camera-outline" style="font-size: 50px;"></ion-icon>
                    @endif
                </div>
                <p><strong>Nome:</strong> {{ $estudante->NomeEstudante ?: 'Sem dados' }}</p>
                <p><strong>Escola:</strong> {{ $estudante->escola ?: 'Sem dados' }}</p>
                <p><strong>Data de Nascimento:</strong> {{ $estudante->datanascimento ?: 'Sem dados' }}</p>
                <p><strong>Telefone:</strong> {{ $estudante->telefone ?: 'Sem dados' }}</p>
                <p>
                    <strong>Turno:</strong>
                    {{ $estudante->Turno ?: 'Sem dados' }} -
                    {{ $estudante->HoraIda ?: 'Sem dados' }} -
                    {{ $estudante->HoraRegresso ?: 'Sem dados' }}
                </p>
                <p>
                    <strong>Rota:</strong>
                    {{ $estudante->NomeRota ?: 'Sem dados' }} -
                    {{ $estudante->PontoA ?: 'Sem dados' }} -
                    {{ $estudante->PontoB ?: 'Sem dados' }}
                </p>
                <div class="text-center">
                    <a href="{{ route('DesfazerConexao',$estudante->id)}}" class="btn">Remover da lista</a>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>

@endsection

