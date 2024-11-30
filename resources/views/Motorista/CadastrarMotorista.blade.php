@extends('layouts.main')
@section('title','Cadastrar Motorista')
@section('content')
<div class="container d-flex justify-content-center mt-5" id="CadastrarMotorista">
    <div class="col-md-8 col-lg-6">
        <h2 class="text-center mb-4">Cadastro de Motorista</h2>
        <form>
            <!-- Dados Pessoais -->
            <div class="mb-3">
                <label for="nomeCompleto" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="nomeCompleto" required>
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

            <!-- Contato -->
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="tel" class="form-control" id="telefone" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email">
            </div>
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <textarea class="form-control" id="endereco" rows="2"></textarea>
            </div>

            <!-- Documentação -->
            <div class="mb-3">
                <label for="numeroBI" class="form-label">Número do BI</label>
                <input type="text" class="form-control" id="numeroBI" required>
            </div>
            <div class="mb-3">
                <label for="numeroCarta" class="form-label">Número da Carta de Condução</label>
                <input type="text" class="form-control" id="numeroCarta" required>
            </div>
            <div class="mb-3">
                <label for="categoriaCarta" class="form-label">Categoria da Carta de Condução</label>
                <select class="form-select" id="categoriaCarta" required>
                    <option selected disabled>Selecione...</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="validadeCarta" class="form-label">Data de Validade da Carta de Condução</label>
                <input type="date" class="form-control" id="validadeCarta" required>
            </div>

            <!-- Informações do Veículo -->
            <div class="mb-3">
                <label for="registroVeiculo" class="form-label">Número de Registro do Veículo</label>
                <input type="text" class="form-control" id="registroVeiculo" required>
            </div>
            <div class="mb-3">
                <label for="marcaModelo" class="form-label">Marca e Modelo do Veículo</label>
                <input type="text" class="form-control" id="marcaModelo" required>
            </div>
            <div class="mb-3">
                <label for="anoFabricacao" class="form-label">Ano de Fabricação do Veículo</label>
                <input type="number" class="form-control" id="anoFabricacao" required>
            </div>
            <div class="mb-3">
                <label for="capacidadePassageiros" class="form-label">Capacidade de Passageiros</label>
                <input type="number" class="form-control" id="capacidadePassageiros" required>
            </div>

            <!-- Dados de Trabalho -->
            <div class="mb-3">
                <label for="turnoTrabalho" class="form-label">Turno de Trabalho</label>
                <select class="form-select" id="turnoTrabalho" required>
                    <option selected disabled>Selecione...</option>
                    <option value="Manhã">Manhã</option>
                    <option value="Tarde">Tarde</option>
                    <option value="Noite">Noite</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="zonaAtendimento" class="form-label">Zona de Atendimento</label>
                <select class="form-select" id="zonaAtendimento" required>
                    <option selected disabled>Selecione...</option>
                    <option value="Luanda">Luanda</option>
                    <option value="Benguela">Benguela</option>
                    <option value="Huambo">Huambo</option>
                </select>
            </div>

            <!-- Outros -->
            <div class="mb-3">
                <label for="experiencia" class="form-label">Experiência como Motorista (anos)</label>
                <input type="number" class="form-control" id="experiencia" required>
            </div>
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