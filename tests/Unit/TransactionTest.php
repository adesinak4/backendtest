<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_transaction_can_be_created()
    {
        // Create a user (maker)
        $user = User::factory()->create(['role' => 'maker']);

        // Acting as that user
        $this->actingAs($user);

        // Create a transaction
        $response = $this->post('/transactions', [
            'type' => 'credit',
            'description' => 'Initial deposit',
            'amount' => 1000
        ]);

        // Assert the transaction was created
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type' => 'credit',
            'description' => 'Initial deposit',
            'amount' => 1000,
            'status' => 'pending'
        ]);

        // Assert the response was successful
        $response->assertStatus(201);
    }

    /** @test */
    public function a_transaction_can_be_approved_by_checker()
    {
        // Create a user (checker)
        $checker = User::factory()->create(['role' => 'checker']);

        // Create a transaction
        $transaction = Transaction::factory()->create(['status' => 'pending']);

        // Acting as the checker
        $this->actingAs($checker);

        // Approve the transaction
        $response = $this->post("/transactions/{$transaction->id}/approve");

        // Assert the transaction was approved
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'approved'
        ]);

        // Assert the response was successful
        $response->assertStatus(200);
    }
}

