<?php

namespace App\Http\Controllers;

use App\Models\SavingsTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // For database transactions

class SavingsController extends Controller
{
    /**
     * Get the authenticated user's current savings balance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getBalance(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'savings_balance' => $user->savings_balance,
        ]);
    }

    /**
     * Get the authenticated user's savings transaction history.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getTransactions(Request $request)
    {
        $user = Auth::user();
        $transactions = $user->savingsTransactions()->orderByDesc('created_at')->get();
        return response()->json([
            'transactions' => $transactions,
        ]);
    }

    /**
     * Handle a deposit into the user's savings account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        // Use a database transaction to ensure atomicity
        // Both balance update and transaction record must succeed or both fail
        DB::transaction(function () use ($user, $amount, $request) {
            // Update user's savings balance
            $user->savings_balance += $amount;
            $user->save();

            // Record the transaction
            SavingsTransaction::create([
                'user_User_id' => $user->User_id, // Use the custom primary key
                'amount' => $amount,
                'type' => 'deposit',
                'description' => $request->description,
                'current_balance' => $user->savings_balance, // Balance after this transaction
            ]);
        });

        return response()->json([
            'message' => 'Deposit successful!',
            'new_balance' => $user->savings_balance,
        ]);
    }

    /**
     * Handle a withdrawal from the user's savings account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        // Check if user has sufficient funds
        if ($user->savings_balance < $amount) {
            return response()->json(['message' => 'Insufficient funds.'], 400);
        }

        DB::transaction(function () use ($user, $amount, $request) {
            // Update user's savings balance
            $user->savings_balance -= $amount;
            $user->save();

            // Record the transaction
            SavingsTransaction::create([
                'user_User_id' => $user->User_id, // Use the custom primary key
                'amount' => $amount,
                'type' => 'withdrawal',
                'description' => $request->description,
                'current_balance' => $user->savings_balance, // Balance after this transaction
            ]);
        });

        return response()->json([
            'message' => 'Withdrawal successful!',
            'new_balance' => $user->savings_balance,
        ]);
    }
}

