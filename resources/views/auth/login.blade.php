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
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn">Login</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection