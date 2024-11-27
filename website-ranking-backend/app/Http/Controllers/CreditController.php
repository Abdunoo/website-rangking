<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationResponse;
use App\Models\Credit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
    use ApplicationResponse;

    public function index(Request $request)
    {
        $user = $request->user();
        $credits = $user->credits()->latest()->get();

        return $this->json(
            200,
            'Credit history retrieved successfully.',
            $credits
        );
    }

    public function addCredits(Request $request, User $user)
    {
        $this->authorize('admin');

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

        return $this->json(
            200,
            'Credits added successfully.',
            $credit,
        );
    }

    public function deductCredits(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
        ]);

        $user = $request->user();

        if ($user->credits < $request->amount) {
            return $this->json(
                400,
                'Insufficient credits.',
            );
        }

        $user->decrement('credits', $request->amount);

        $credit = Credit::create([
            'user_id' => $user->id,
            'website_id' => $request->website_id,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        return $this->json(
            200,
            'Credits deducted successfully.',
            $credit,
        );
    }
}
