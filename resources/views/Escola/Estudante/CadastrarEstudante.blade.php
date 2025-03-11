@extends('layouts.main')
@section('title','Estudantes')
@section('content')
<form method="POST" action="{{ route('InscreverEstudante') }}" enctype="multipart/form-data">
    @csrf
    <!-- Container do Formulário -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Registrar-se</h4>
                    </div>
                    <div class="card-body">
                        <!--Campo para rotas-->
                        <div class="mb-3">
                            <label for="rotasId" class="form-label">Rotas:</label>
                            <select name="rotasId" id="rotas" class="form-control" required>
                                @foreach($rotas as $rota)
                                    <option value="{{$rota->id}}">{{$rota->nome}}-{{$rota->PontoA}}-{{$rota->PontoB}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!--Campo para Id estudante-->
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Coloca aqui o username do estudante.." required>
                        </div>


                        <!-- Botão de Registrar -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn ">Cadastrar</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div id="space"></div>
@endsection