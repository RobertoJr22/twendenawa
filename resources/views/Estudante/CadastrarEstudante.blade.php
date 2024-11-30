
@extends('layouts.main')
@section('title','Cadastrar Estudante')
@section('content')
<div class="container d-flex justify-content-center mt-5">
    <div class="col-md-8 col-lg-6">
        <h2 class="text-center mb-4">Cadastro de Estudante</h2>
        <form>
            <!-- Dados Pessoais -->
            <div class="mb-3">
                <label for="nomeEstudante" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="nomeEstudante" required>
            </div>
            <div class="mb-3">
                <label for="dataNascimento" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" id="dataNascimento" required>
            </div>
            <div class="mb-3">
                <label for="genero" class="form-label">Gênero</label>
                <select class="form-select" id="genero" required>
                    <option selected disabled>Selecione...</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Feminino">Feminino</option>
                    <option value="Outro">Outro</option>
                </select>
            </div>

            <!-- Dados de Contato -->
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="tel" class="form-control" id="telefone">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email">
            </div>
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <textarea class="form-control" id="endereco" rows="2"></textarea>
            </div>

            <!-- Informações Escolares -->
            <div class="mb-3">
                <label for="matricula" class="form-label">Número de Matrícula</label>
                <input type="text" class="form-control" id="matricula" required>
            </div>
            <div class="mb-3">
                <label for="instituicao" class="form-label">Instituição de Ensino</label>
                <input type="text" class="form-control" id="instituicao" required>
            </div>
            <div class="mb-3">
                <label for="grauEnsino" class="form-label">Grau de Ensino</label>
                <select class="form-select" id="grauEnsino" required>
                    <option selected disabled>Selecione...</option>
                    <option value="Fundamental">Fundamental</option>
                    <option value="Médio">Médio</option>
                    <option value="Superior">Superior</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="anoLetivo" class="form-label">Ano Letivo</label>
                <input type="number" class="form-control" id="anoLetivo" required>
            </div>

            <!-- Informações dos Pais ou Responsáveis -->
            <div class="mb-3">
                <label for="nomeResponsavel" class="form-label">Nome do Responsável</label>
                <input type="text" class="form-control" id="nomeResponsavel" required>
            </div>
            <div class="mb-3">
                <label for="contatoResponsavel" class="form-label">Contato do Responsável</label>
                <input type="tel" class="form-control" id="contatoResponsavel" required>
            </div>

            <!-- Observações Adicionais -->
            <div class="mb-3">
                <label for="observacoes" class="form-label">Observações Adicionais</label>
                <textarea class="form-control" id="observacoes" rows="2"></textarea>
            </div>

            <!-- Botão de Ação -->
            <button type="submit" class="btn btn-primary w-100">Salvar Cadastro</button>
        </form>
    </div>
</div>
<div id="space"></div>
@endsection