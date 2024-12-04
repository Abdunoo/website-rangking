<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationResponse;
use App\Models\Review;
use App\Models\User;
use App\Models\Website;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    use ApplicationResponse;
    use AuthorizesRequests;
    /**
     * Display reviews for a specific website
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);

        $reviews = Review::with('website', 'user')->orderByDesc('id')->paginate($limit);

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
            $review->user->photo = $review->user->photo ? Storage::url($review->user->photo) : null;

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
     * Update the average rating for all websites
     */
    public function updateAllWebsiteRatings()
    {
        $websites = Website::all();

        foreach ($websites as $website) {
            $approvedReviews = Review::where('website_id', $website->id)
                ->where('is_approved', true)
                ->get();

            $averageRating = $approvedReviews->avg('rating');

            $website->rating = round($averageRating, 2);
            $website->save();
        }

        return $this->json(200, 'All website ratings updated successfully.');
    }

    public function updateAllReview(Request $requesr)
    {
        try {
            $status = $requesr->input('status') == 'approve' ? 0 : 1;
            $reviews = Review::where('is_approved', $status)->get();
            foreach ($reviews as $review) {
                $review->is_approved = !$status;
                $review->save();
            }
            return $this->json(
                200,
                'Reviews updated successfully.'
            );
        } catch (\Throwable $th) {
            // throw $th;
            return $this->json(
                500,
                'Failed to update reviews'
            );
        }
    }

    public function approveReview($reviewId)
    {
        $review = Review::find($reviewId);

        if (!$review) {
            return $this->json(404, 'Review not found.');
        }

        if ($review->is_approved) {
            return $this->json(400, 'Review is already approved.');
        }

        $review->is_approved = true;
        $review->save();

        $website = Website::find($review->website_id);
        if (!$website) {
            return $this->json(404, 'Website not found.');
        }

        $approvedReviews = Review::where('website_id', $website->id)
            ->where('is_approved', true)
            ->get();

        $averageRating = $approvedReviews->avg('rating');
        $website->rating = round($averageRating, 2);
        $website->save();

        return $this->json(
            200,
            'Review approved and website rating updated successfully.',
            $review
        );
    }

    public function rejectReview($reviewId)
    {
        $review = Review::find($reviewId);

        if (!$review) {
            return $this->json(404, 'Review not found.');
        }

        if ($review->is_approved === false) {
            return $this->json(400, 'Review is already rejected.');
        }

        $review->is_approved = false;
        $review->save();

        $website = Website::find($review->website_id);
        if (!$website) {
            return $this->json(404, 'Website not found.');
        }

        $approvedReviews = Review::where('website_id', $website->id)
            ->where('is_approved', true)
            ->get();

        $averageRating = $approvedReviews->avg('rating');
        $website->rating = round($averageRating, 2);
        $website->save();

        return $this->json(
            200,
            'Review rejected and website rating updated successfully.',
            $review
        );
    }

    /**
     * Update an existing review
     */
    public function update(Request $request, $reviewId)
    {
        $review = Review::findOrFail($reviewId);

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
