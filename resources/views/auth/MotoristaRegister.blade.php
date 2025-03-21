@extends('layouts.main')
@section('tilte','Cadastrar Motorista')
@section('content')
<form method="POST" action="{{route('RegistarMotorista')}}" enctype="multipart/form-data">
    @csrf
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Cadastrar Motorista</h4>
                    </div>
                    <div class="card-body">
                        <!-- Campo de Nome -->
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome:</label>
                            <input type="text" class="form-control" name="name" value="{{old('name')}}">
                            @error('name')<p class="text-danger text-sm">{{ $message }}</p>@enderror
                        </div>

                        <!-- Campo de Data de Nascimento -->
                        <div class="mb-3">
                            <label for="DataNascimento" class="form-label">Data de nascimento:</label>
                            <input type="date" class="form-control" name="DataNascimento" id="DataNascimento" value="{{old('DataNascimento')}}">
                            @error('DataNascimento')<p class="text-danger text-sm">{{ $message }}</p>@enderror
                        </div>

                        <!-- Campo de BI -->
                        <div class="mb-3">
                            <label for="BI" class="form-label">BI:</label>
                            <input type="text" class="form-control" name="BI" id="BI" value="{{old('BI')}}">
                            @error('BI')<p class="text-danger text-sm">{{ $message }}</p>@enderror
                        </div>

                        <!-- Campo de Numero da Carta -->
                        <div class="mb-3">
                            <label for="NumeroCarta" class="form-label">Numero da Carta:</label>
                            <input type="text" class="form-control" name="NumeroCarta" id="NumeroCarta" value="{{old('NumeroCarta')}}">
                            @error('NumeroCarta')<p class="text-danger text-sm">{{ $message }}</p>@enderror
                        </div>

                        <!-- Campo de Endereco -->
                        <div class="mb-3">
                            <label for="Endereco" class="form-label">Endereço:</label>
                            <input type="text" class="form-control" name="endereco" id="Endereco" value="{{old('endereco')}}">
                            @error('endereco')<p class="text-danger text-sm">{{ $message }}</p>@enderror
                        </div>

                        <!-- Campo de Sexo -->
                        <div class="mb-3">
                            <label for="sexos_id" class="form-label">Sexo:</label>
                            <select name="sexos_id" class="form-control form-select">
                                <option value="1">Masculino</option>
                                <option value="2">Feminino</option>
                            </select>
                        </div>

                        <!-- Campo de Turno -->
                        <div class="mb-3">
                            <label for="turnos_id" class="form-label">Turno:</label>
                            <select name="turnos_id" class="form-control form-select">
                                @foreach($turnos as $turno)
                                    <option value="{{$turno->id}}">{{$turno->nome}} - {{$turno->HoraIda}} - {{$turno->HoraRegresso}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Campo de Telefone -->
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone:</label>
                            <input type="number" class="form-control" name="telefone" id="telefone" max="999999999" value="{{old('telefone')}}">
                            @error('telefone')<p class="text-danger text-sm">{{ $message }}</p>@enderror
                        </div>

                        <!-- Campo de Foto -->
                        <div class="mb-3">
                            <label for="foto" class="form-label">Coloca uma foto:</label>
                            <input type="file" class="form-control" name="foto" id="foto" value="{{old('foto')}}">
                            @error('foto')<p class="text-danger text-sm">{{ $message }}</p>@enderror
                        </div>

                        <!-- Campo de Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" value="{{old('email')}}">
                            @error('email')<p class="text-danger text-sm">{{ $message }}</p>@enderror
                        </div>

                        <!-- Campo de Senha -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha:</label>
                            <input type="password" class="form-control" name="password">
                            @error('password')<p class="text-danger text-sm">{{ $message }}</p>@enderror
                        </div>

                        <!-- Campo de Confirmação de Senha -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Senha:</label>
                            <input type="password" class="form-control" name="password_confirmation">
                            @error('password_confirmation')<p class="text-danger text-sm">{{ $message }}</p>@enderror
                        </div>

                        <!-- Campo de Tipo de Usuário -->
                        <input type="hidden" name="tipo_usuario_id" value="3">

                        <!-- Botão de Registrar -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn w-100">Registrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div id="space"></div>
@endsection
