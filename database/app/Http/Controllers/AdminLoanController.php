<?php

namespace App\Http\Controllers;

use App\Models\LoanRequest;
use App\Models\User; // If you're updating user loan balance
use Illuminate\Http\Request;

class AdminLoanController extends Controller
{
    /**
     * Get all pending loan requests for admin review.
     *
     * @return \Illuminate\Http\Response
     */
    public function pending()
    {
        $pendingLoans = LoanRequest::where('status', 'pending')
                                   ->orderByDesc('requested_on')
                                   ->get();

        return response()->json($pendingLoans);
    }

    /**
     * Approve a specific loan request.
     *
     * @param  string  $loanId
     * @return \Illuminate\Http\Response
     */
    public function approve($loanId)
    {
        $loan = LoanRequest::find($loanId);

        if (!$loan) {
            return response()->json(['message' => 'Loan request not found.'], 404);
        }

        if ($loan->status !== 'pending') {
            return response()->json(['message' => 'Loan is not pending approval.'], 400);
        }

        $loan->status = 'approved';
        $loan->last_updated = now();
        $loan->save();

        // Optional: Update user's loan balance
        // Make sure you have a User model and 'loan_balance' column
        // $user = User::where('user_id', $loan->user_id)->first(); // Adjust based on your User model's unique identifier
        // if ($user) {
        //     $user->loan_balance += $loan->amount;
        //     $user->save();
        // }

        return response()->json(['message' => "Loan {$loanId} approved successfully."]);
    }

    /**
     * Deny a specific loan request.
     *
     * @param  string  $loanId
     * @return \Illuminate\Http\Response
     */
    public function deny($loanId)
    {
        $loan = LoanRequest::find($loanId);

        if (!$loan) {
            return response()->json(['message' => 'Loan request not found.'], 404);
        }

        if ($loan->status !== 'pending') {
            return response()->json(['message' => 'Loan is not pending rejection.'], 400);
        }

        $loan->status = 'rejected';
        $loan->last_updated = now();
        $loan->save();

        return response()->json(['message' => "Loan {$loanId} rejected successfully."]);
    }
}