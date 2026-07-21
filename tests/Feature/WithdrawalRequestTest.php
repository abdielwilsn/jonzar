<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WithdrawalRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_withdrawal_request_debits_the_gross_amount_and_stores_the_net_payout()
    {
        Mail::fake();

        DB::table('settings')->insert([
            'site_name' => 'Test Platform',
            'currency' => '$',
            'contact_email' => 'ops@example.com',
            'withdrawal_percentage' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /** @var User $user */
        $user = User::factory()->create([
            'account_bal' => 1000,
        ]);

        $response = $this->actingAs($user)
            ->from(route('withdrawalsdeposits'))
            ->post(route('withdrawamount'), [
                'amount' => 95,
                'gross_amount' => 100,
                'wallet_address' => '0x1234567890abcdef',
                'network' => 'BSC',
                'notes' => 'Test withdrawal',
            ]);

        $response->assertRedirect(route('withdrawalsdeposits'));
        $response->assertSessionHas('success');

        $user->refresh();

        $this->assertSame('900.00', number_format((float) $user->account_bal, 2, '.', ''));

        $withdrawal = Withdrawal::first();

        $this->assertNotNull($withdrawal);
        $this->assertSame('95.00', number_format((float) $withdrawal->amount, 2, '.', ''));
        $this->assertSame('100.00', number_format((float) $withdrawal->to_deduct, 2, '.', ''));
        $this->assertSame($user->id, $withdrawal->user_id);
    }

    public function test_user_cannot_submit_another_withdrawal_while_one_is_pending()
    {
        Mail::fake();

        DB::table('settings')->insert([
            'site_name' => 'Test Platform',
            'currency' => '$',
            'contact_email' => 'ops@example.com',
            'withdrawal_percentage' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /** @var User $user */
        $user = User::factory()->create([
            'account_bal' => 1000,
        ]);

        Withdrawal::create([
            'user_id' => $user->id,
            'amount' => 95,
            'to_deduct' => 100,
            'wallet_address' => '0xexistingpendingrequest',
            'network' => 'BSC',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->from(route('withdrawalsdeposits'))
            ->post(route('withdrawamount'), [
                'amount' => 190,
                'gross_amount' => 200,
                'wallet_address' => '0x1234567890abcdef',
                'network' => 'BSC',
                'notes' => 'Second withdrawal attempt',
            ]);

        $response->assertRedirect(route('withdrawalsdeposits'));
        $response->assertSessionHas('message', 'You already have a pending withdrawal request.');

        $user->refresh();

        $this->assertSame('1000.00', number_format((float) $user->account_bal, 2, '.', ''));
        $this->assertSame(1, Withdrawal::count());
    }
}
