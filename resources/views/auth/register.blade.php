@extends('layouts.main')
@section('title','Bem-Vindo')
@section('content')
<form method="POST" action="{{ route('register') }}">
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
                            <label for="name" class="form-label">Nome:</label>
                            <input type="text" class="form-control" name="name" required>
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
                        <div class="mb-3">
                            <label for="tipo_usuario_id" class="form-label">Tipo de Usuário:</label>
                            <select name="tipo_usuario_id" class="form-select" required>
                                <option value="1">Admin</option>
                                <option value="2">Estudante</option>
                                <option value="3">Motorista</option>
                                <option value="4">Responsavel</option>
                            </select>
                        </div>

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
@endsection