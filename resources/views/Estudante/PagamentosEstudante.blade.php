@extends('layouts.main')
@section('title', 'Pagamento')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Card do formulário de pagamento -->
            <div class="card">
                <div class="card-header text-center">
                    <h3>Realize seu Pagamento</h3>
                </div>
                <div class="card-body">
                    <!-- Formulário de pagamento -->
                    <form action="" method="POST">
                        @csrf

                        <!-- Número do cartão -->
                        <div class="mb-3">
                            <label for="card_number" class="form-label">Número do Cartão</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="card_number" 
                                   name="card_number" 
                                   placeholder="Digite o número do cartão" 
                                   required>
                        </div>

                        <!-- Nome do titular -->
                        <div class="mb-3">
                            <label for="card_holder" class="form-label">Nome do Titular</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="card_holder" 
                                   name="card_holder" 
                                   placeholder="Digite o nome do titular" 
                                   required>
                        </div>

                        <!-- Data de expiração -->
                        <div class="mb-3">
                            <label for="expiry_date" class="form-label">Data de Expiração</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="expiry_date" 
                                   name="expiry_date" 
                                   placeholder="MM/AA" 
                                   required>
                        </div>

                        <!-- Código de segurança (CVC) -->
                        <div class="mb-3">
                            <label for="cvc" class="form-label">CVC</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="cvc" 
                                   name="cvc" 
                                   placeholder="Código de segurança" 
                                   required>
                        </div>

                        <!-- Valor do pagamento -->
                        <div class="mb-3">
                            <label for="amount" class="form-label">Valor</label>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control" 
                                   id="amount" 
                                   name="amount" 
                                   placeholder="Valor do pagamento" 
                                   required>
                        </div>

                        <!-- Botão de pagamento -->
                        <button type="submit" class="btn btn-primary w-100">
                            Pagar
                        </button>
                    </form>
                </div>
            </div>
            <!-- Fim do card -->
        </div>
    </div>
</div>
@endsection
