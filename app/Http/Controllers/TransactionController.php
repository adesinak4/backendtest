<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\TransactionStatusNotification;

class TransactionController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'maker') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $wallet = $user->wallet;
        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
            ]);
        }
        return view('transactions.create');

        $request->validate([
            'type' => 'required|in:credit,debit',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'description' => $request->description,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        Log::info('Transaction created', [
            'user_id' => Auth::id(),
            'transaction_id' => $transaction->id,
            'type' => $request->type,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        return response()->json(['transaction' => $transaction], 201);
    }

    public function store(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // dd($user);

        if ($user->role !== 'maker') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate incoming request
        $request->validate([
            'type' => 'required|in:credit,debit',  // Type must be either credit or debit
            'description' => 'required|string',    // Description is required
            'amount' => 'required|numeric|min:0',  // Amount is required and must be a number
        ]);

        // Create a transaction for the authenticated user (Maker)
        $transaction = Transaction::create([
            'user_id' => Auth::id(),               // Get the currently authenticated user's ID
            'type' => $request->type,              // Use the request's type (credit or debit)
            'description' => $request->description, // Use the request's description
            'amount' => $request->amount,          // Use the request's amount
            'status' => 'pending',                 // Set the status to 'pending' by default
        ]);

        // Return a JSON response with the created transaction
        return response()->json(['transaction' => $transaction], 201);
    }

    public function pending()
    {
        $user = Auth::user();

        if ($user->role !== 'checker') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }


        $pendingTransactions = Transaction::where('status', 'pending')->get();
        return view('transactions.pending', compact('pendingTransactions'));

        return response()->json(['pendingTransactions' => $pendingTransactions]);
    }

    public function approve($id)
    {
        $user = Auth::user();

        if ($user->role !== 'checker') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if ($transaction->status !== 'pending') {
            return response()->json(['message' => 'Transaction already processed'], 400);
        }

        // Proceed with transaction approval logic
        DB::transaction(function () use ($transaction) {
            $wallet = $transaction->user->wallet;
            $systemPool = Wallet::find(1);

            if ($transaction->type === 'credit') {
                $wallet->balance += $transaction->amount;
                $systemPool->balance -= $transaction->amount;
            } else {
                if ($wallet->balance < $transaction->amount) {
                    throw new \Exception('Insufficient funds');
                }
                $wallet->balance -= $transaction->amount;
                $systemPool->balance += $transaction->amount;
            }

            $transaction->status = 'approved';
            $transaction->save();
            $wallet->save();
            $systemPool->save();
        });

        Log::info('Transaction approved', [
            'checker_id' => Auth::id(),
            'transaction_id' => $transaction->id,
        ]);

        $transaction->user->notify(new TransactionStatusNotification($transaction, 'approved'));

        return response()->json(['message' => 'Transaction approved']);
    }


    public function reject($id)
    {
        $user = Auth::user();

        if ($user->role !== 'checker') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if ($transaction->status !== 'pending') {
            return response()->json(['message' => 'Transaction already processed'], 400);
        }

        $transaction->status = 'rejected';
        $transaction->save();

        Log::info('Transaction rejected', [
            'checker_id' => Auth::id(),
            'transaction_id' => $transaction->id,
        ]);

        $transaction->user->notify(new TransactionStatusNotification($transaction, 'rejected'));

        return response()->json(['message' => 'Transaction rejected']);
    }
}
