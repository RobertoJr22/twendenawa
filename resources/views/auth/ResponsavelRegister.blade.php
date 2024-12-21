@extends('layouts.main')
@section('title','Twendenawa')
@section('content')
<form method="POST" action="{{ route('RegistarResponsavel') }}" enctype="multipart/form-data">
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
                        <!-- Campo de Nome -->
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome:</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <!-- Campo de Data de Nascimento -->
                        <div class="mb-3">
                            <label for="DataNascimento" class="form-label">Data de nascimento:</label>
                            <input type="date" class="form-control" name="DataNascimento" id="DataNascimento" required>
                        </div>

                        <!-- Campo de BI -->
                        <div class="mb-3">
                            <label for="BI" class="form-label">BI:</label>
                            <input type="text" class="form-control" name="BI" id="BI" required>
                        </div>

                        <!-- Campo de Endereco -->
                        <div class="mb-3">
                            <label for="Endereco" class="form-label">Endereco:</label>
                            <input type="text" class="form-control" name="Endereco" id="Endereco" required>
                        </div>

                        <!-- Campo de Sexo -->
                        <div class="mb-3">
                            <label for="sexos_id" class="form-label">Sexo:</label>
                            <select name="sexos_id" class="form-select" required>
                                <option value="1">Masculino</option>
                                <option value="2">Feminino</option>
                            </select>
                        </div>

                        <!-- Campo de Telefone -->
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone:</label>
                            <input type="number" class="form-control" name="telefone" id="telefone" max="999999999" required>
                        </div>

                        <!-- Campo de foto -->
                        <div class="mb-3">
                            <label for="foto" class="form-label">Coloca uma foto:</label>
                            <input type="file" class="form-control" name="foto" id="foto" placeholder="opcional">
                        </div>

                        <!-- Campo de Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <!-- Campo de Senha -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha:</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <!-- Campo de Confirmação de Senha -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Senha:</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <!-- Campo de Tipo de Usuário -->
                        <input type="hidden" name="tipo_usuario_id" value="4">

                        <!-- Botão de Registrar -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn ">Registrar</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div id="space"></div>
@endsection