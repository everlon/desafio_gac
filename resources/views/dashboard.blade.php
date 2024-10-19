<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Bem-vindo!") }}

                    <h4>
                        Saldo atual: R$ {{ number_format($saldo ?? 0, 2, ',', '.') }}
                        <a class="fs-6 ml-2" href="{{ route('transactions.index') }}">(Extrato)</a>
                    </h4>
                    <hr>

                    <div class="row grid gap-3">
                      <div class="col-5">
                          <!-- Formulário de Depósito -->
                          <div class="deposit-form">
                              <h3>Depositar na Carteira</h3>

                              <!-- Formulário -->
                              <form action="{{ route('wallet.deposit') }}" method="POST">
                                  @csrf <!-- Token CSRF para proteger o formulário -->

                                  <div class="row form-group">
                                    <div class="col">
                                      <label for="amount">Valor do Depósito:</label>
                                      <input type="number" name="amount" id="amount" class="form-control" min="0.01" step="0.01" placeholder="Digite o valor" required>
                                    </div>
                                    <div class="col-auto">
                                      <br>
                                      <button type="submit" class="btn btn-primary">Depositar</button>
                                    </div>
                                  </div>
                              </form>
                          </div>
                      </div>

                      <div class="col">
                          <!-- Formulário de Transferencia -->
                          <div class="deposit-form">
                              <h3>Transferência</h3>

                              <!-- Formulário -->
                              <form action="{{ route('wallet.transfer') }}" method="POST">
                                  @csrf <!-- Token CSRF para proteger o formulário -->

                                  <div class="row form-group">
                                    <div class="col">
                                      <label for="amount">Valor do Transferência:</label>
                                      <input type="number" name="amount" id="amount" class="form-control" min="0.01" step="0.01" placeholder="Digite o valor" required>
                                    </div>
                                    <div class="col">
                                      <label for="amount">Número da conta destino:</label>
                                      <input type="number" name="receiver_count" id="receiver_count" class="form-control" min="0.01" step="0.01" placeholder="Digite a conta de destino" required>
                                    </div>
                                    <div class="col-auto">
                                        <br>
                                      <button type="submit" class="btn btn-primary">Transferir</button>
                                    </div>
                                  </div>

                              </form>
                      </div>
                    </div>

                    <!-- Verifica se há mensagens de sucesso ou erro -->
                    @if(session('success'))
                        <div class="alert alert-success text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger text-center">
                            @foreach($errors->all() as $error)
                                {{ $error }}<br />
                            @endforeach
                        </div>
                    @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
