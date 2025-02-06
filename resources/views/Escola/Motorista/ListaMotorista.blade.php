@extends('layouts.main')
@section('title','Dashboard')
@section('content')
<!-- Container principal da Dashboard de Motoristas -->
<div class="container mt-5">
    <h2 class="text-center mb-4 fs-responsive">Dashboard de Motoristas</h2>

    <!-- Barra de Pesquisa de Motoristas -->
    <form action="{{route('ListaMotorista')}}" method="get">
        @csrf
        <div class="row justify-content-center mb-4">
            <div class="col-md-6 col-sm-8 col-10">
                <input type="text" class="form-control" name="search" placeholder="Pesquisar Motorista... " value="{{request('search')}}">
            </div>
            <button class="btn" type="submit">Pesquisar</button>
        </div>
     </form>

    <!-- Tabela de Motoristas -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Licença</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Rota</th>
                    <th>Turno</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            @foreach($motoristas as $motorista)
            <tr>
            <td>{{ $motorista->id }}</td>
            <td>{{ $motorista->nome ?? 'N/A' }}</td>
            <td>{{ $motorista->telefone ?? 'N/A' }}</td>
            <td>{{ $motorista->licenca ?? 'N/A' }}</td>

            @if($motorista->estado == 2)
                <td>{{ $motorista->marcas ?? 'N/A' }}</td>
                <td>{{ $motorista->modelos ?? 'N/A' }}</td>
                <td>{{ $motorista->rotas ?? 'N/A' }}</td>
            @else
                <td>N/A</td>
                <td>N/A</td>
                <td>N/A</td>
            @endif

            <td>{{ $motorista->Turnos ?? 'N/A' }}</td>
            <td>
                <button class="btn btn-sm btn-custom">Editar</button>
                <button class="btn btn-sm btn-custom">Desvincular</button>
            </td>
        </tr>
            @endforeach

            </tbody>
        </table>
    </div>

    <!-- Botão de Cadastrar Novo Motorista -->
    <div class="text-center mt-3">
        <a href="/Escola/Motorista/AdAssociar" class="btn btn-custom">Adicionar/Associar Motorista</a>
    </div>
</div>
<div id="space"></div>
@endsection