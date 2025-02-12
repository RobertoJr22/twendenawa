@extends('layouts.main')
@section('tilte','Escola Motorista')
@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Primeiro Formulário -->
        <div class="col-md-6">
            <div class="card p-4">
                <h2 class="text-center mb-4 fs-responsive">Adicionar novo motorista</h2>
                <form method="POST" action="{{route('Associar')}}" enctype="multipart/form-data">
                    @csrf
                    <!-- Campo para BI -->
                    <div class="mb-3">
                        <label for="BI" class="form-label">BI:</label>
                        <input type="text" class="form-control" name="BI" value="{{old('BI')}}">
                    </div>
                    
                    <input type="hidden" name="opcao" value="2">

                    <button type="submit" class="btn btn-custom w-100">Adicionar</button>

                    
                </form>
            </div>
        </div>

        <!-- Segundo Formulário -->
        <div class="col-md-6">
            <div class="card p-4">
                <h2 class="text-center mb-4 fs-responsive">Associar</h2>
                <form method="POST" action="{{route('Associar')}}" enctype="multipart/form-data">
                    @csrf
                    <!-- Campo de Rota -->
                    <div class="mb-3">
                        <label for="rotas_id" class="form-label">Rota:</label>
                        <select name="rotas_id" class="form-control form-select">
                            @foreach($rotas as $rota)
                            <option value="{{$rota->id}}">{{$rota->nome}}-{{$rota->PontoA}}-{{$rota->PontoB}}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Campo de Veiculo -->
                    <div class="mb-3">
                        <label for="veiculos_id" class="form-label">Veiculo:</label>
                        <select name="veiculos_id" class="form-control form-select">
                            @foreach($veiculos as $veiculo)
                            <option value="{{$veiculo->id}}">{{$veiculo->marca}}-{{$veiculo->modelo}}-{{$veiculo->matricula}}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Campo de motorista -->
                    <div class="mb-3">
                        <label for="motoristas_id" class="form-label">Motorista:</label>
                        <select name="motoristas_id" class="form-control form-select">
                            @foreach($motoristas as $motorista)
                            <option value="{{$motorista->motorista->id}}">{{$motorista->motorista->user->name}}-{{$motorista->motorista->carteira->NumeroCarta}}-{{$motorista->motorista->turno->nome}}</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="opcao" value="1">

                    <button type="submit" class="btn btn-custom w-100">Associar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection