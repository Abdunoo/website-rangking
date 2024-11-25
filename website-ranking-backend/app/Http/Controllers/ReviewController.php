<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationResponse;
use App\Models\Review;
use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    use ApplicationResponse;
    /**
     * Display reviews for a specific website
     */
    public function index(Request $request, $websiteId)
    {
        $reviews = Review::where('website_id', $websiteId)
            ->where('is_approved', true)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'reviews' => $reviews,
            'total_reviews' => $reviews->total()
        ];

        return $this->json(
            200,
            'Reviews retrieved successfully',
            $reviews
        );
    }

    /**
     * Create a new review
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'website_id' => 'required|exists:websites,id',
            'content' => 'required|string|min:10|max:500',
            'rating' => 'nullable|integer|min:1|max:5'
        ]);

        if ($validator->fails()) {
            return $this->json(
                422,
                $validator->errors()
            );
        }

        try {
            $review = Review::create([
                'user_id' => Auth::id(),
                'website_id' => $request->input('website_id'),
                'content' => $request->input('content'),
                'rating' => $request->input('rating'),
                'is_approved' => false // Requires admin approval
            ]);
            $review->user = User::find($review->user_id);

            return $this->json(
                200,
                'Review submitted successfully',
                $review
            );
        } catch (\Exception $e) {
            return $this->json(
                500,
                'Failed to create review'
            );
        }
    }

    /**
     * Update an existing review
     */
    public function update(Request $request, $reviewId)
    {
        $review = Review::findOrFail($reviewId);

        // Ensure user can only edit their own reviews
        $this->authorize('update', $review);

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:10|max:500',
            'rating' => 'nullable|integer|min:1|max:5'
        ]);

        if ($validator->fails()) {
            return $this->json(
                422,
                $validator->errors()
            );
        }

        $review->update([
            'content' => $request->input('content'),
            'rating' => $request->input('rating'),
            'is_approved' => false // Reset approval status
        ]);

        return $this->json(
            200,
            'Review updated successfully',
            $review
        );
    }

    /**
     * Delete a review
     */
    public function destroy($reviewId)
    {
        $review = Review::findOrFail($reviewId);

        // Ensure user can only delete their own reviews
        $this->authorize('delete', $review);

        $review->delete();

        return $this->json(
            200,
            'Review deleted successfully'
        );
    }

    /**
     * Get user's own reviews
     */
    public function userReviews()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with('website')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $this->json(
            200,
            'Reviews retrieved successfully',
            $reviews,
        );
    }

    /**
     * Admin method to approve reviews
     */
    public function approveReview($reviewId)
    {
        // Ensure only admins can approve reviews
        $this->authorize('admin', Review::class);

        $review = Review::findOrFail($reviewId);
        $review->update(['is_approved' => true]);

        return $this->json(
            200,
            'Review approved successfully',
            $review
        );
    }

    /**
     * Get reviews statistics
     */
    public function reviewStats($websiteId)
    {
        $stats = Review::where('website_id', $websiteId)
            ->where('is_approved', true)
            ->selectRaw('
                COUNT(*) as total_reviews,
                AVG(rating) as average_rating,
                COUNT(DISTINCT user_id) as unique_reviewers
            ')
            ->first();

        return $this->json(
            200,
            'Review statistics retrieved successfully',
            $stats
        );
    }
}
