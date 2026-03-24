<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_update_their_transaction(): void
    {
        $user = User::factory()->create();
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 1250,
            'category' => 'Rent',
            'description' => 'Office rent',
            'date' => '2026-03-10',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('transactions.update', $transaction->id), [
                'type' => 'sale',
                'amount' => 3200.50,
                'category' => 'Inventory',
                'description' => 'Updated income',
                'date' => '2026-03-12',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $transaction->refresh();

        $this->assertSame('sale', $transaction->type);
        $this->assertSame('3200.50', $transaction->amount);
        $this->assertSame('Sales', $transaction->category);
        $this->assertSame('Updated income', $transaction->description);
        $this->assertSame('2026-03-12', $transaction->date->format('Y-m-d'));
    }

    public function test_user_cannot_update_another_users_transaction(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $transaction = Transaction::create([
            'user_id' => $owner->id,
            'type' => 'expense',
            'amount' => 500,
            'category' => 'Utilities',
            'description' => 'Electric bill',
            'date' => '2026-03-05',
        ]);

        $response = $this
            ->actingAs($intruder)
            ->put(route('transactions.update', $transaction->id), [
                'type' => 'expense',
                'amount' => 900,
                'category' => 'Salary',
                'description' => 'Tampered entry',
                'date' => '2026-03-06',
            ]);

        $response->assertNotFound();

        $transaction->refresh();

        $this->assertSame('500.00', $transaction->amount);
        $this->assertSame('Utilities', $transaction->category);
        $this->assertSame('Electric bill', $transaction->description);
        $this->assertSame('2026-03-05', $transaction->date->format('Y-m-d'));
    }

    public function test_dashboard_shows_edit_action_for_transactions(): void
    {
        $user = User::factory()->create();
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 700,
            'category' => 'Other',
            'description' => 'Packaging',
            'date' => '2026-03-11',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Edit entry', false);
    }
}
