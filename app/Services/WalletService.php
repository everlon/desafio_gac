<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class WalletService extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        function deposit(User $user, float $amount)
        {
            DB::transaction(function () use ($user, $amount) {
                $wallet = $user->wallet;
                $wallet->balance += $amount;
                $wallet->save();

                Transaction::create([
                    'receiver_id' => $user->id,
                    'amount' => $amount,
                    'type' => 'deposit',
                ]);
            });
        }

        function transfer(User $sender, User $receiver, float $amount)
        {
            DB::transaction(function () use ($sender, $receiver, $amount) {
                $senderWallet = $sender->wallet;
                $receiverWallet = $receiver->wallet;

                if ($senderWallet->balance < $amount) {
                    throw new \Exception('Saldo insuficiente.');
                }

                $senderWallet->balance -= $amount;
                $senderWallet->save();

                $receiverWallet->balance += $amount;
                $receiverWallet->save();

                Transaction::create([
                    'sender_id' => $sender->id,
                    'receiver_id' => $receiver->id,
                    'amount' => $amount,
                    'type' => 'transfer',
                ]);
            });
        }

        function reverseTransaction(Transaction $transaction)
        {
            DB::transaction(function () use ($transaction) {
                if ($transaction->type == 'transfer') {
                    $senderWallet = $transaction->sender->wallet;
                    $receiverWallet = $transaction->receiver->wallet;

                    $senderWallet->balance += $transaction->amount;
                    $receiverWallet->balance -= $transaction->amount;
                    $senderWallet->save();
                    $receiverWallet->save();
                } elseif ($transaction->type == 'deposit') {
                    $receiverWallet = $transaction->receiver->wallet;
                    $receiverWallet->balance -= $transaction->amount;
                    $receiverWallet->save();
                }
            });
        }
    }


    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
