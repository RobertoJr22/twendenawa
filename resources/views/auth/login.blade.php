@extends('layouts.main')
@section('title','Bem-Vindo')
@section('content')

<form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Container do Formulário -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Login</h4>
                    </div>
                    <div class="card-body">

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

                        <!-- Botão de Login -->
                        <div class="d-grid gap-2" style="margin: 3px;">
                            <button type="submit" class="btn w-100">Login</button>
                        </div>
                        <div style="display: flex; align-items: center; text-align: center;">
                        <hr style="flex: 1; border: none; border-top: 1px solid #000;">
                        <span style="padding: 0 10px;">ou</span>
                        <hr style="flex: 1; border: none; border-top: 1px solid #000;">
                        </div>
                        <div class="cadastrar">
                            <a href="/auth/Selecao" class="cadastrar-link">Cadastrar-se aqui</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection