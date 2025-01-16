@extends('layouts.main')
@section('title','Veiculos')
@section('content')
<!-- Container principal da Dashboard -->
<div class="container mt-5">
    <h2 class="text-center mb-4 fs-responsive">Lista de Escolas</h2>
    
    <!-- Barra de Pesquisa -->
     <form action="{{route('ListaVeiculo')}}" method="get">
        @csrf
        <div class="row justify-content-center mb-4">
            <div class="col-md-6 col-sm-8 col-10">
                <input type="text" class="form-control" name="search" placeholder="Pesquisar veiculo... " value="{{request('search')}}">
            </div>
            <button class="btn" type="submit">Pesquisar</button>
        </div>
     </form>

    <!-- Tabela de Veiculo -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Matricula</th>
                    <th>VIN</th>
                    <th>Rota</th>
                    <th>Motorista</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($veiculos as $veiculo)
                    <tr>
                        <td>{{$veiculo->id}}</td>
                        <td>{{$veiculo->modelo?->marcas?->nome ?? 'Sem marca'}}</td>
                        <td>{{$veiculo->modelo?->nome ?? 'Sem modelo'}}</td>
                        <td>{{$veiculo->Matricula}}</td>
                        <td>{{$veiculo->VIN}}</td>
                        <td>
                            @if($veiculo->rotas->isNotEmpty())
                                @foreach($veiculo->rotas as $rota)
                                    {{$rota->nome}} ({{$rota->PontoA}} - {{$rota->PontoB}})
                                @endforeach
                            @else
                                Sem rota
                            @endif
                        </td>
                        <td>
                            @if($veiculo->motoristas->isNotEmpty())
                                @foreach($veiculo->motoristas as $motorista)
                                    {{$motorista->nome}}
                                @endforeach
                            @else
                                Sem motorista
                            @endif
                        </td>
                        <td id="action">
                            <button class="btn btn-sm btn-custom action mb-1" onclick="editarVeiculo">Editar</button>
                            <button class="btn btn-sm btn-custom action mb-1" onclick="deletarVeiculo">Deletar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Botão de Cadastrar Novo Veiculo -->
    <div class="text-center mt-4">
        <a class="btn btn-custom" href="/Escola/Veiculo/CadastrarVeiculo">Cadastrar novo veiculo</a>
    </div>
</div>
<div id="space"></div>
@endsection