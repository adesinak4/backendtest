<?php

namespace App\Listeners;

use App\Models\Wallet;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateWalletForUser
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        // Create a wallet for the newly registered user
        Wallet::create([
            'user_id' => $event->user->id,
            'balance' => 0,  // Initial balance is set to 0
        ]);
    }
}
