<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\User;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    /**
     * Display the user's credit history.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $credits = $user->credits()->latest()->get();

        return response()->json([
            'message' => 'Credit history retrieved successfully.',
            'data' => $credits,
        ]);
    }

    /**
     * Add credits to a user account (admin-only).
     */
    public function addCredits(Request $request, User $user)
    {
        $this->authorize('admin'); // Pastikan pengguna adalah admin

        $request->validate([
            'amount' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
        ]);

        $user->increment('credits', $request->amount);

        $credit = Credit::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Credits added successfully.',
            'data' => $credit,
        ], 201);
    }

    /**
     * Deduct credits from a user account.
     */
    public function deductCredits(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
        ]);

        if ($user->credits < $request->amount) {
            return response()->json([
                'message' => 'Insufficient credits.',
            ], 400);
        }

        $user->decrement('credits', $request->amount);

        $credit = Credit::create([
            'user_id' => $user->id,
            'amount' => -$request->amount,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Credits deducted successfully.',
            'data' => $credit,
        ], 201);
    }
}
