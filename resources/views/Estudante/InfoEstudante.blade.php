@extends('layouts.main')
@section('title', 'Informações do Estudante')
@section('content')
<div class="position-relative top-0 start-0 m-3">
    <a href="{{ url()->previous() }}" class="btn">Voltar</a>
</div>
<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="container" style="max-width: 600px;">
        <h2 class="text-center mb-4">Informações do Estudante</h2>

        <div class="card mb-3">
            <div class="card-body text-center">
                <div class="profile-photo-container" id="profile-photo" style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden; margin: 0 auto;">
                    @if ($estudantes && $estudantes->foto)
                        <img src="{{ asset('storage/' . $estudantes->foto) }}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                    @else
                        <ion-icon name="camera-outline" style="font-size: 50px; display: block; margin: auto;"></ion-icon>
                    @endif
                </div>
                <p><strong>Nome:</strong> {{ $estudantes->NomeEstudante ?: 'Sem dados' }}</p>
                <p><strong>Escola:</strong> {{ $estudantes->escola ?: 'Sem dados' }}</p>
                <p><strong>Data de Nascimento:</strong> {{ $estudantes->datanascimento ?: 'Sem dados' }}</p>
                <p><strong>Telefone:</strong> {{ $estudantes->telefone ?: 'Sem dados' }}</p>
                <p>
                    <strong>Turno:</strong>
                    {{ $estudantes->Turno ?: 'Sem dados' }} -
                    {{ $estudantes->HoraIda ?: 'Sem dados' }} -
                    {{ $estudantes->HoraRegresso ?: 'Sem dados' }}
                </p>
                <p>
                    <strong>Rota:</strong>
                    {{ $estudantes->NomeRota ?: 'Sem dados' }} -
                    {{ $estudantes->PontoA ?: 'Sem dados' }} -
                    {{ $estudantes->PontoB ?: 'Sem dados' }}
                </p>
                 <!-- Responsaveis do estudante -->
                 <div id="Outros-Responsaveis" class="card mt-3">
                    @if(auth()->user()->tipo_usuario_id == 3)
                        <h5>Responsáveis do estudante</h5>
                    @else
                        <h5>Outros responsáveis do estudante</h5>
                    @endif
                    <div class="card-body">
                        <div class="list-group overflow">
                        @if ($responsaveis->isEmpty())
                            <span>Nenhum outro responsável</span>
                        @else
                            @foreach($responsaveis as $responsavel)
                                <a href="{{route('InfoResponsavel',$responsavel->id)}}" class="student-link btn btn-lista d-flex justify-content-between align-items-center mb-3">
                                    <span>{{ $responsavel->nome }}</span>
                                    <span>Ver Mais</span>
                                </a>
                            @endforeach
                        @endif
                        </div>
                    </div>
                </div>
                @if(auth()->user()->tipo_usuario_id == 4)
                <div class="text-center">
                    <a href="{{ route('DesfazerConexao',$estudantes->id)}}" class="btn">Desfazer Conexão</a>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection

