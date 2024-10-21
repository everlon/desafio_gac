<?php

namespace Tests\Unit;

use App\Models\Wallet;
use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use App\Services\CarteiraService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_creates_a_transaction(): void
    {
        $sender = User::factory()->create();
        $transaction = Transaction::create([
            'receiver_id' => $sender->id,
            'amount' => 100,
            'type' => 'deposit'
        ]);

        $this->assertEquals(100, $transaction->amount);
        $this->assertEquals('deposit', $transaction->type);
    }


    public function test_it_can_reverse_a_transaction(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $walletService = new CarteiraService();

        $sender->wallet->update(['balance' => 100]);
        $receiver->wallet->update(['balance' => 0]);

        Transaction::create([
            'sender_id' => $sender->id,
            'amount' => 100,
            'type' => 'deposit'
        ]);

        $walletService->transfer($sender, $receiver, 50);
        $transaction = Transaction::where('type', 'transfer')->where('sender_id', $sender->id)->first();

        // Revertendo a transação
        $walletService->reverseTransaction($transaction);

        $sender_wallet = Wallet::where('user_id', $sender->id)->first();
        $receiver_wallet = Wallet::where('user_id', $receiver->id)->first();

        $this->assertEquals(100, $sender_wallet->balance);
        $this->assertEquals(0, $receiver_wallet->balance);
    }
}
