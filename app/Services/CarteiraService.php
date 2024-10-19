<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;


class CarteiraService
{
        public function deposit(User $user, float $amount)
        {
            DB::transaction(function () use ($user, $amount) {
                $user->load('wallet');

                // Verifica se o usuário tem uma carteira, e cria uma se não tiver
                $wallet = $user->wallet ?: $user->wallet()->create(['balance' => 0,]);

                $wallet->balance += $amount;
                $wallet->save();

                Transaction::create([
                    'receiver_id' => $user->id,
                    'amount' => $amount,
                    'type' => 'deposit',
                ]);
            });
        }

        public function transfer(User $sender, User $receiver, float $amount)
        {
            DB::transaction(function () use ($sender, $receiver, $amount) {
                $sender->load('wallet');
                $receiver->load('wallet');

                // Verifica se o usuário tem uma carteira, e cria uma se não tiver
                $senderWallet = $sender->wallet ?: $sender->wallet()->create(['balance' => 0,]);
                $receiverWallet = $receiver->wallet ?: $receiver->wallet()->create(['balance' => 0,]);

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

        public function reverseTransaction(Transaction $transaction)
        {
            try {
                DB::transaction(function () use ($transaction) {
                    if ($transaction->type == 'transfer') {
                        $senderWallet = $transaction->sender->wallet;
                        $receiverWallet = $transaction->receiver->wallet;

                        $senderWallet->balance += $transaction->amount;
                        $receiverWallet->balance -= $transaction->amount;

                        $senderWallet->save();
                        $receiverWallet->save();
                    } elseif ($transaction->type == 'deposit')
                    {
                        $receiverWallet = $transaction->receiver->wallet;
                        $receiverWallet->balance -= $transaction->amount;

                        $receiverWallet->save();
                    }

                    $transaction->delete(); // Exclusão da transação.
                });

            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }

        // Método para obter o saldo da carteira de um usuário
        public function getBalance(User $user)
        {
            // Obtém a carteira do usuário autenticado e retorna o saldo
            $wallet = $user->wallet; // Considerando o relacionamento 'wallet' no modelo User
            return $wallet ? $wallet->balance : 0;
        }

}
