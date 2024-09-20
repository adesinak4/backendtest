<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
    ];

    /**
     * The user who owns the wallet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Increase wallet balance.
     */
    public function credit($amount)
    {
        $this->balance += $amount;
        $this->save();
    }

    /**
     * Decrease wallet balance if sufficient funds.
     */
    public function debit($amount)
    {
        if ($this->balance < $amount) {
            throw new \Exception('Insufficient funds');
        }
        $this->balance -= $amount;
        $this->save();
    }
}
