<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Services\CarteiraService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Definir a data de 6 meses atrás somente.
        $sixMonthsAgo = Carbon::now()->subMonths(6);

        $sentTransactions = $user->sentTransactions()
                                     ->where('created_at', '>=', $sixMonthsAgo)
                                     ->with('receiver')
                                     ->get();

        $receivedTransactions = $user->receivedTransactions()
                                        ->where('created_at', '>=', $sixMonthsAgo)
                                        ->with('sender')
                                        ->get();

        return view('transactions.index', [
            'transactions' => [
                'sender' => $sentTransactions,
                'receiver' => $receivedTransactions,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction, $id)
    {
        $user = Auth::user();

        // A transação deve pertencer ao usuário (como remetente ou destinatário)
        $transaction = Transaction::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })->findOrFail($id);

        return response()->json($transaction, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    /**
     * Reverter uma transação (caso de inconsistência ou solicitação do usuário)
     */
    public function reverse(CarteiraService $carteira, $id)
    {
        $user = Auth::user();

        $transaction = Transaction::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })->findOrFail($id);

        // dd($transaction);

        try {
            $carteira->reverseTransaction($transaction);

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        return redirect()->route('dashboard')->with('success', 'Transação revertida com sucesso.');
    }
}
