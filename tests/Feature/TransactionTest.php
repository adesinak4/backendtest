<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleBasedAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_maker_can_access_create_transaction_page()
    {
        // Create users with different roles
        $maker = User::factory()->create(['role' => 'maker']);
        $checker = User::factory()->create(['role' => 'checker']);

        // Maker should be able to access the page
        $this->actingAs($maker)
             ->get('/transactions/create')
             ->assertStatus(200);

        // Checker should be forbidden
        $this->actingAs($checker)
             ->get('/transactions/create')
             ->assertStatus(403);
    }

    /** @test */
    public function only_checker_can_approve_transactions()
    {
        // Create a checker
        $checker = User::factory()->create(['role' => 'checker']);

        // Acting as the checker
        $this->actingAs($checker)
             ->post("/transactions/1/approve")
             ->assertStatus(200);

        // Acting as a maker should return a forbidden response
        $maker = User::factory()->create(['role' => 'maker']);

        $this->actingAs($maker)
             ->post("/transactions/1/approve")
             ->assertStatus(403);
    }
}
