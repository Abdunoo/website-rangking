<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationResponse;
use App\Models\Credit;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function deductCredits(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
        ]);

        if ($user->credits < $request->amount) {
            return $this->json(
                400,
                'Insufficient credits.',
            );
        }

        $user->decrement('credits', $request->amount);
        $user->user_can_access_contact = 1;
        $user->save();

        $credit = Credit::create([
            'user_id' => $user->id,
            'amount' => -$request->amount,
            'description' => $request->description,
        ]);

        return $this->json(
            200,
            'Credits deducted successfully.',
            $credit,
        );
    }
}
