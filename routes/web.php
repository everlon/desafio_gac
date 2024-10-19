<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () { return view('welcome'); });

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'dashboard'])->name('dashboard');

    // Rotas do Perfil do usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas para Carteira
    Route::get('/wallet', [WalletController::class, 'showBalance'])->name('wallet.showBalance');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/wallet/transfer', [WalletController::class, 'transfer'])->name('wallet.transfer');

    // Rotas para Transações
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{id}/reverse', [TransactionController::class, 'reverse'])->name('transactions.reverse');
});

require __DIR__.'/auth.php';
