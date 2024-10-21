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
        // Wallet::factory()->create(['user_id' => $user->id, 'balance' => 100]);

        $user->wallet ?: $user->wallet()->create(['balance' => 0,]);
        $user->wallet->balance += 100;
        $user->wallet->save();

        $this->actingAs($user)
             ->get(route('wallet.showBalance'))
             ->assertStatus(200)
             ->assertViewHas('saldo', 100);
    }

    public function test_it_can_make_a_deposit(): void
    {
        $user = User::factory()->create();
        // Wallet::factory()->create(['user_id' => $user->id, 'balance' => 100]);

        $user->wallet ?: $user->wallet()->create(['balance' => 0,]);
        $user->wallet->balance += 100;
        $user->wallet->save();

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

        $sender->wallet->update(['balance' => 100]);
        $receiver->wallet->update(['balance' => 50]);


        $response = $this->actingAs($sender)
             ->post(route('wallet.transfer'), ['receiver_count' => $receiver->count_number, 'amount' => 50])
             ->assertStatus(302)  // Redireciona de volta
             // ->assertSessionHas('success', 'Transferência realizada com sucesso.')
            ;

        // dd($sender);
        // dd($receiver);

        $response->assertStatus(302)
            ->assertRedirect()
            ->assertSessionHas(['success' => 'Transferência realizada com sucesso.']);

        $sender_wallet = Wallet::where('user_id', $sender->id)->first();
        $receiver_wallet = Wallet::where('user_id', $receiver->id)->first();

        $this->assertEquals(50, $sender_wallet->balance);
        $this->assertEquals(100, $receiver_wallet->balance);
    }

    public function test_it_canot_transfer_funds_to_another_user_with_no_balance(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $sender->wallet->update(['balance' => 100]);
        $receiver->wallet->update(['balance' => 50]);

        $response = $this->actingAs($sender)
             ->post(route('wallet.transfer'), ['receiver_count' => $receiver->count_number, 'amount' => 200])
             ->assertStatus(302)  // Redireciona de volta
             // ->assertSessionHas('transfer', 'Saldo insuficiente.')
            ;

        // dd($response);

        $response->assertStatus(302)
            ->assertRedirect()
            ->assertSessionHasErrors(['transfer' => 'Saldo insuficiente.']);

        $sender_wallet = Wallet::where('user_id', $sender->id)->first();
        $receiver_wallet = Wallet::where('user_id', $receiver->id)->first();

        $this->assertEquals(100, $sender_wallet->balance);
        $this->assertEquals(50, $receiver_wallet->balance);
    }
}
