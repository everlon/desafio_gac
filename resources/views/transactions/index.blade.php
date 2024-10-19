<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <table class="table table-striped table-auto border-separate border border-spacing-2">
                        <caption class="caption-top">Transações Enviadas</caption>
                        <thead>
                            <tr>
                                <th class="border border-slate-600">Destinatário</th>
                                <th class="border border-slate-600">Valor</th>
                                <th class="border border-slate-600">Data</th>
                                <th class="border border-slate-600"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions['sender'] as $transaction)
                                <tr>
                                    <td class="border border-slate-700">
                                        @if($transaction->type === 'deposit')
                                            Depósito
                                        @else
                                            {{ $transaction->receiver->name }}
                                        @endif
                                    </td class="border border-slate-700">
                                    <td class="border border-slate-700">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                                    <td class="border border-slate-700">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="col-2">
                                        <form method="POST" action="{{ route('transactions.reverse', $transaction->id) }}">
                                            @csrf

                                            <a class="btn btn-primary"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                {{ __('Reverter transação') }}
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <br><br>

                    <table class="table table-striped table-auto border-separate border border-spacing-2">
                        <caption class="caption-top">Transações Recebidas</caption>
                        <thead>
                            <tr>
                                <th class="border border-slate-600">Remetente</th>
                                <th class="border border-slate-600">Valor</th>
                                <th class="border border-slate-600">Data</th>
                                <th class="border border-slate-600"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions['receiver'] as $transaction)
                                <tr>
                                    <td class="border border-slate-700">
                                        @if($transaction->type === 'deposit')
                                            Depósito
                                        @else
                                            {{ $transaction->sender->name }}
                                        @endif
                                    </td class="border border-slate-700">
                                    <td class="border border-slate-700">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                                    <td class="border border-slate-700">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="col-2">
                                        <form method="POST" action="{{ route('transactions.reverse', $transaction->id) }}">
                                            @csrf

                                            <input type="hidden" value="{{ $transaction->id }}">
                                            <a href="{{ route('transactions.reverse', $transaction->id) }}" class="btn btn-primary"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                {{ __('Reverter transação') }}
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
