@extends('layouts.main')
@section('title','Cadastrar Escola')
@section('content')
<div class="container d-flex justify-content-center mt-5">
    <div class="col-md-8 col-lg-6">
        <h2 class="text-center mb-4 fs-responsive">Cadastro de Escola</h2>
        <form method="POST" action="{{route('CadastrarEscola')}}">
            @csrf
            <!-- Nome -->
            <div class="mb-3">
                <label for="name" class="form-label">Nome da instituição:</label>
                <input type="text" class="form-control" id="Nome" name="name">
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
                <label for="password_confirmation" class="form-label">Confirme a Senha:</label>
                <input type="password" class="form-control" name="password_confirmation" required>
            </div>


            <!-- Telefone -->
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="tel" class="form-control" id="telefone" name="telefone" required>
            </div>


            <!-- municipio -->
            <div class="form-group mb-3">
                <label for="municipio">Município</label>
                <select class="form-control" id="municipio" name="municipio_id">
                    <option value="">Selecione o município</option>
                    @foreach ($municipios as $municipio)
                        <option value="{{ $municipio->id }}">{{ $municipio->nome }}</option>
                    @endforeach
                </select>
            </div>


            <!-- distrito -->
            <div class="form-group mb-3">
                <label for="distrito">Distrito</label>
                <select class="form-control" id="distrito" name="distrito_id">
                    <option value="">Selecione o distrito</option>
                </select>
            </div>

            <!-- Bairros -->
            <div class="form-group mb-3">
                <label for="bairro">Bairro</label>
                <select class="form-control" id="bairro" name="bairros_id">
                    <option value="">Selecione o bairro</option>
                </select>
            </div>

            <!-- Campo de Tipo de Usuário -->
            <input type="hidden" value="5" name="tipo_usuario_id">

            <!-- Botão de Ação -->
            <button type="submit" class="btn btn-custom w-100">Salvar Cadastro</button>
        </form>
    </div>
</div>
<div id="space"></div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('#municipio').on('change', function () {
            const municipioId = $(this).val();
            $('#distrito').html('<option value="">Selecione um distrito</option>').prop('disabled', true);
            $('#bairro').html('<option value="">Selecione um bairro</option>').prop('disabled', true);

            if (municipioId) {
                $.get(`/distritos/${municipioId}`, function (data) {
                    $('#distrito').prop('disabled', false);
                    data.forEach(function (distrito) {
                        $('#distrito').append(`<option value="${distrito.id}">${distrito.nome}</option>`);
                    });
                });
            }
        });

        $('#distrito').on('change', function () {
            const distritoId = $(this).val();
            $('#bairro').html('<option value="">Selecione um bairro</option>').prop('disabled', true);

            if (distritoId) {
                $.get(`/bairros/${distritoId}`, function (data) {
                    $('#bairro').prop('disabled', false);
                    data.forEach(function (bairro) {
                        $('#bairro').append(`<option value="${bairro.id}">${bairro.nome}</option>`);
                    });
                });
            }
        });
    });
</script>
@endsection
