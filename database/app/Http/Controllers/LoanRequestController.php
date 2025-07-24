<?php

namespace App\Http\Controllers;

use App\Models\LoanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // For validation

class LoanRequestController extends Controller
{
    /**
     * Submit a new loan request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $loanRequest = LoanRequest::create([
            'user_id' => $request->input('userId'),
            'amount' => $request->input('amount'),
            'status' => 'pending', // Default status
            'requested_on' => now(),
            'last_updated' => now(),
        ]);

        return response()->json($loanRequest, 201); // 201 Created
    }

    /**
     * Get a user's loan status history.
     *
     * @param  string  $userId
     * @return \Illuminate\Http\Response
     */
    public function status($userId)
    {
        $loanHistory = LoanRequest::where('user_id', $userId)
                                  ->orderByDesc('requested_on')
                                  ->get();

        return response()->json($loanHistory);
    }
}
