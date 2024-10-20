<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WalletFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_show_user_balance_on_dashboard(): void
    {
        $user = User::factory()->create();
        Wallet::factory()->create(['user_id' => $user->id, 'balance' => 100]);

        $this->actingAs($user)
             ->get(route('wallet.showBalance'))
             ->assertStatus(200)
             ->assertViewHas('saldo', 100);
    }

    public function test_it_can_make_a_deposit(): void
    {
        $user = User::factory()->create();
        Wallet::factory()->create(['user_id' => $user->id, 'balance' => 100]);

        $this->actingAs($user)
             ->post(route('wallet.deposit'), ['amount' => 50])
             ->assertStatus(302)  // Redireciona de volta
             ->assertSessionHas('success', 'Depósito realizado com sucesso.');

        $this->assertEquals(150, $user->wallet->balance);
    }

    public function test_it_can_transfer_funds_to_another_user(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        Wallet::factory()->create(['user_id' => $sender->id, 'balance' => 100]);
        Wallet::factory()->create(['user_id' => $receiver->id, 'balance' => 50]);

        $this->actingAs($sender)
             ->post(route('wallet.transfer'), ['receiver_id' => $receiver->id, 'amount' => 50])
             ->assertStatus(302)  // Redireciona de volta
             ->assertSessionHas('success', 'Transferência realizada com sucesso.');

        $this->assertEquals(50, $sender->wallet->balance);
        $this->assertEquals(100, $receiver->wallet->balance);
    }
}
