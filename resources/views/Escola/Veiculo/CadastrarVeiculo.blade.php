@extends('layouts.main')
@section('title','Cadastrar Viatura')
@section('content')
<div class="container d-flex justify-content-center mt-5">
    <div class="col-md-8 col-lg-6">
        <h2 class="text-center mb-4 fs-responsive">Cadastrar Veiculo</h2>
        <form method="POST" action="{{route('CadastrarVeiculo')}}">
            @csrf
            <!-- Marca do veículo -->
            <div class="mb-3">
                <label for="marcas_id" class="form-label">Marca:</label>
                <select name="marcas_id" id="marcas_id" class="form-control">
                    <option value="">Selecione a marca</option>
                    @foreach($marcas as $marca)
                        <option value="{{ $marca->id }}">{{ $marca->nome }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Modelo do veículo -->
            <div class="mb-3">
                <label for="modelos_id" class="form-label">Modelo:</label>
                <select name="modelos_id" id="modelos_id" class="form-control">
                    <option value="">Selecione o modelo</option>
                </select>
            </div>


            <!-- Matricula -->
            <div class="mb-3">
                <label for="matricula" class="form-label">Matricula:</label>
                <input type="text" class="form-control" name="matricula" required>
            </div>

            <!-- VIN -->
            <div class="mb-3">
                <label for="VIN" class="form-label">Número de identificação do veículo(VIN):</label>
                <input type="text" class="form-control" name="VIN" required>
            </div>


            <!-- Capacidade -->
            <div class="mb-3">
                <label for="Capacidade" class="form-label">Capacidade de lotação:</label>
                <input type="number" class="form-control" name="capacidade" required>
            </div>


            <!-- Botão de Ação -->
            <button type="submit" class="btn btn-custom w-100">Salvar Cadastro</button>
        </form>
    </div>
</div>
<div id="space"></div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('#marcas_id').on('change', function() {
            var marcaId = $(this).val(); // Obtém o ID da marca selecionada
            var modeloSelect = $('#modelos_id');

            // Limpa o select de modelos
            modeloSelect.empty();
            modeloSelect.append('<option value="">Selecione o modelo</option>');

            if (marcaId) {
                // Faz a requisição AJAX
                $.ajax({
                    url: '/modelos/' + marcaId,
                    type: 'GET',
                    success: function(data) {
                        // Preenche o select de modelos com os dados retornados
                        data.forEach(function(modelo) {
                            modeloSelect.append('<option value="' + modelo.id + '">' + modelo.nome + '</option>');
                        });
                    },
                    error: function() {
                        alert('Erro ao buscar modelos. Tente novamente.');
                    }
                });
            }
        });
    });
</script>
@endsection