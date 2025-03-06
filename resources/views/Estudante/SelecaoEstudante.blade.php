@extends('layouts.main')
@section('title','Adicionar estudantes a bordo')
@section('content')
<!-- Container principal -->
<div class="custom-container">
    <h2 class="text-center mb-4 fs-responsive">Pesquisar Estudante</h2>

    <!-- Barra de Pesquisa -->
    <form action="{{ route('aBordo') }}" method="get">
        @csrf
        <div class="input-group mb-4">
            <input type="text" class="form-control" name="search" placeholder="Digite o Id do estudante..." value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Pesquisar</button>
        </div>
    </form>


    <div class="card dados-pessoais">
        @if(!$estudante)
            <p>{{$Info}}</p>
        @else
            <!-- Foto de Perfil -->
            <div class="profile-photo-container" id="profile-photo">
                @if ($estudante && $estudante->foto)
                    <img src="{{ asset('storage/'.$estudante->foto) }}" alt="">
                @else
                    <ion-icon name="camera-outline"></ion-icon>
                @endif
            </div>

            <!-- Dados Pessoais -->
            <div class="informacao-usuario">
                <h5 class="card-title">Informações do Estudante</h5>
                <p class="dados-usuario"><strong>Nome:</strong>{{ $estudante->nome}}</p>
                <p class="dados-usuario"><strong>Email:</strong> {{ $estudante->email}}</p>
                <p class="dados-usuario"><strong>Telefone:</strong>{{ $estudante->telefone}}</p>
            </div>
            <div class="btn-SelecaoEstudante">
                @if(isset($estudante) && !$abordo)
                    <a href="{{ route('AdicionarAbordo', $estudante->id) }}" class="btn editar btn-custom dados-usuario">
                        Adicionar <ion-icon name="add-circle-outline"></ion-icon>
                    </a>
                @elseif(isset($estudante))
                    <a href="{{ route('RemoverAbordo', $estudante->id) }}" class="btn editar btn-custom dados-usuario">
                        Remover <ion-icon name="remove-circle-outline"></ion-icon>
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection