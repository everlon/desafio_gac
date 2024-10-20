<?php

namespace Tests\Unit;

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
        $receiver = User::factory()->create();
        $transaction = Transaction::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'amount' => 100,
            'type' => 'transfer'
        ]);

        $this->assertEquals(100, $transaction->amount);
        $this->assertEquals('transfer', $transaction->type);
    }


    public function test_it_can_reverse_a_transaction(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $walletService = new CarteiraService();

        $walletService->transfer($sender, $receiver, 50);
        $transaction = Transaction::where('sender_id', $sender->id)->first();

        // Revertendo a transação
        $walletService->reverseTransaction($transaction);

        $this->assertEquals(100, $sender->wallet->balance);
        $this->assertEquals(0, $receiver->wallet->balance);
    }
}
