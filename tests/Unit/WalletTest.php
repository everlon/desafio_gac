<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Wallet;
use App\Services\CarteiraService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WalletTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_can_get_the_balance_of_the_wallet(): void
    {
        $user = User::factory()->create();
        $user->wallet->update(['balance' => 100]);

        $carteiraService = new CarteiraService();
        $balance = $carteiraService->getBalance($user);

        $this->assertEquals(100, $balance);
    }

    public function test_it_can_deposit_money_in_the_wallet(): void
    {
        $user = User::factory()->create();
        $user->wallet->update(['balance' => 100]);

        $carteiraService = new CarteiraService();
        $carteiraService->deposit($user, 50);

        $this->assertEquals(150, $user->wallet->balance);
    }


    public function test_it_can_transfer_money_to_another_user(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        // Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 100]);
        // Wallet::factory()->create(['user_id' => $receiver->id, 'balance' => 50]);

        $sender->wallet->update(['balance' => 100]);
        $receiver->wallet->update(['balance' => 50]);

        $carteiraService = new CarteiraService();
        $carteiraService->transfer($sender, $receiver, 50);

        $this->assertEquals(50, $sender->wallet->balance);
        $this->assertEquals(100, $receiver->wallet->balance);
    }
}
