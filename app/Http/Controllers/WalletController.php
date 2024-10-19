<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Services\CarteiraService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;


class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreWalletRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Wallet $wallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWalletRequest $request, Wallet $wallet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        //
    }

    // Exibir o saldo da carteira do usuário autenticado
    public function showBalance(CarteiraService $carteiraService)
    {
        $user = Auth::user();

        $balance = $carteiraService->getBalance($user);

        return view('dashboard', [
            'saldo' => $balance,
        ]);
    }

    // Realizar um depósito na carteira do usuário autenticado
    public function deposit(Request $request, CarteiraService $carteiraService)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        $carteiraService->deposit($user, $validated['amount']);

        return redirect()->route('wallet.showBalance')->with([
            'success' => 'Depósito realizado com sucesso.',
            'saldo' => $user->wallet->balance,
        ]);
    }

    // Realizar uma transferência de saldo da carteira do usuário autenticado para outro usuário
    public function transfer(Request $request, CarteiraService $carteiraService)
    {

        $validated = $request->validate([
            'receiver_count' => 'required|exists:users,count_number',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $sender = Auth::user();

        $receiver = User::where('count_number', $validated['receiver_count'])->firstOrFail();


        if (!$receiver) {
            return redirect()->back()->withErrors(['error' => 'Número de Conta não encontrado.']);
        }

        $amount = $validated['amount'];

        try {
            $carteiraService->transfer($sender, $receiver, $amount);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['transfer' => $e->getMessage()]);
        }

        return redirect()->route('dashboard')->with('success', 'Transferência realizada com sucesso.');
    }
}
