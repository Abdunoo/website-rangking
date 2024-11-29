<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationResponse;
use App\Helpers\DataHelper;
use App\Models\Credit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
    use ApplicationResponse;
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $oneMonthAgo = Carbon::now()->subMonth();

        $credits = Credit::with('user')->orderByDesc('created_at')->get();

        $activeUsers = Credit::where('created_at', '>=', $oneMonthAgo)
            ->distinct('user_id')
            ->count('user_id');

        $pendingTransactions = Credit::where('status', 'pending')->count();

        $data = [
            'credits' => $credits,
            'totalCredits' => $credits->sum('amount'),
            'activeUsers' => $activeUsers,
            'pendingTransactions' => $pendingTransactions,
        ];

        return $this->json(
            200,
            'Credit history retrieved successfully.',
            $data
        );
    }

    public function getHistoryPayment(Request $request)
    {
        $user = Auth::user();
        $creditsHis = $user->credits()->where('type', DataHelper::PURCHASE)->orderByDesc('id')->get();

        return $this->json(
            200,
            'Credit history retrieved successfully.',
            $creditsHis
        );
    }

    public function addCredits(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'amount' => 'required|integer|min:1',
            'payment_method' => 'required|string|max:255',
        ]);

        $credit = Credit::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'description' => $request->description,
            'type' => DataHelper::PURCHASE,
            'payment_method' => $request->payment_method,
        ]);

        return $this->json(
            200,
            'Credits added successfully.',
            $credit
        );
    }

    // Function to approve purchase credit
    public function approveCredit($creditId)
    {
        $credit = Credit::find($creditId);

        if (!$credit) {
            return $this->json(404, 'Credit not found.', null);
        }

        if ($credit->status !== DataHelper::PENDING) {
            return $this->json(400, 'Credit is not in pending status.', null);
        }

        $credit->status = DataHelper::APPROVED;
        $credit->save();

        $user = $credit->user;
        $user->increment('credits', $credit->amount);

        return $this->json(
            200,
            'Credit approved successfully.',
            $credit
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
            'type' => DataHelper::DEDUCTION,
            'status' => DataHelper::APPROVED
        ]);

        return $this->json(
            200,
            'Credits deducted successfully.',
            $credit,
        );
    }
}
