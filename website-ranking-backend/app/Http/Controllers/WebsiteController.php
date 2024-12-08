<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationResponse;
use App\Models\Contact;
use App\Models\Review;
use App\Models\Website;
use App\Models\WebsiteTrends;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isEmpty;

class WebsiteController extends Controller
{
    use ApplicationResponse;
    use AuthorizesRequests;

    protected $reviewController;

    public function __construct(ReviewController $reviewController)
    {
        $this->reviewController = $reviewController;
    }

    public function updateRankings(Request $request)
    {
        try {
            $this->reviewController->updateAllWebsiteRatings();

            $websites = Website::with(['reviews' => function ($query) {
                $query->where('is_approved', true);
            }, 'websiteTrends'])->get();

            foreach ($websites as $website) {
                $averageRating = $website->reviews->avg('rating');
                $trendsCount = $website->websiteTrends->count();
                $website['score'] = $this->calculateScore($averageRating, $trendsCount);
            }

            $websites = $websites->sortByDesc('score')->values();

            $rank = 1;
            foreach ($websites as $website) {
                $website->previous_rank = $website->rank;
                $website->rank = $rank;
                $website->save(['rank', 'previous_rank']);
                $rank++;
            }

            return $this->json(200, 'Rankings updated successfully.');
        } catch (\Exception $e) {
            return $this->json(500, 'Error updating rankings: ' . $e->getMessage());
        }
    }

    private function calculateScore($averageRating, $trendsCount)
    {
        $ratingWeight = config('rankings.rating_weight', 0.7);
        $trendsWeight = config('rankings.trends_weight', 0.3);

        $normalizedRating = $averageRating ? $averageRating * 20 : 0;

        return ($normalizedRating * $ratingWeight) + ($trendsCount * $trendsWeight);
    }

    /**
     * Display a listing of websites.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 50);
        $orderBy = $request->input('order_by', 'rank');
        $searchTerm = '%' . $request->input('search', '') . '%';
        $categoryId = $request->input('cat', '');

        $websites = Website::with('categories', 'contacts')
            ->where('domain', 'LIKE', $searchTerm)
            ->when($categoryId, function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->orderBy($orderBy)
            ->paginate($limit);

        return $this->json(
            200,
            'Websites retrieved successfully.',
            $websites
        );
    }

    public function getLstWebsites(Request $request)
    {
        $limit = $request->input('limit', 50);
        $orderBy = $request->input('order_by', 'rank');
        $searchTerm = '%' . $request->input('search', '') . '%';
        $categoryId = $request->input('cat', '');

        $websites = Website::with('categories')
            ->where('domain', 'LIKE', $searchTerm)
            ->when($categoryId, function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->orderBy($orderBy)
            ->paginate($limit);

        return $this->json(
            200,
            'Websites retrieved successfully.',
            $websites
        );
    }

    public function byName($name)
    {
        $user = Auth::user();

        $website = Website::with([
            'contacts',
            'reviews' => function ($query) {
                $query->where('is_approved', true);  // Apply condition to reviews
            },
            'reviews.user'
        ])
            ->where('name', $name)
            ->first();

        if (!$website) {
            return $this->json(
                404,
                'Website not found.',
                null
            );
        }

        $website->view_contact = $website->credits()
            ->where('user_id', $user->id)
            ->where('credits.website_id', $website->id)
            ->exists();

        foreach ($website->reviews as $review) {
            $photoPath = $review->user->photo;
            $review->user->photo = $photoPath && !str_starts_with($photoPath, 'http')
                ? Storage::url($photoPath)
                : $photoPath;
        }

        $website_trends = WebsiteTrends::create([
            'user_id' => $user->id,
            'website_id' => $website->id,
        ]);

        return $this->json(
            200,
            'Website retrieved successfully.',
            $website
        );
    }

    /**
     * Store a new website.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'domain' => 'required|string|max:255|unique:websites',
        ]);

        $latestRank = Website::max('rank');

        $newRank = $latestRank ? $latestRank + 1 : 1;

        $website = Website::create(array_merge($request->all(), ['rank' => $newRank, 'previous_rank' => $newRank]));

        $user = Auth::user();

        if (!empty($request->input('email'))) {
            $contactEmail = Contact::create(
                [
                    'type' => 'email',
                    'value' => $request->input('email'),
                    'website_id' => $website->id,
                    'user_id' => $user->id,
                ]
            );
        }

        if (!empty($request->input('phone'))) {
            $contactPhone = Contact::create(
                [
                    'type' => 'phone',
                    'value' => $request->input('phone'),
                    'website_id' => $website->id,
                    'user_id' => $user->id,
                ]
            );
        }

        $website->contacts = [
            $contactEmail,
            $contactPhone
        ];

        return $this->json(
            200,
            'Website created successfully.',
            $website,
        );
    }

    /**
     * Update an existing website.
     */
    public function update(Request $request, Website $website)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'domain' => 'required|string|max:255|unique:websites,domain,' . $website->id,
            'email' => 'sometimes|required|string|email|max:255,value,' .($request->input('email') ?? $website->contacts->firstWhere('type', 'phone')->value ?? ''),
            'phone' => 'sometimes|required|string|max:20,value,' .($request->input('phone') ?? $website->contacts->firstWhere('type', 'email')->value ?? ''),
        ]);

        $website->update($request->all());

        $contactEmail = Contact::where('website_id', $website->id)->where('type', 'email')->first();
        if ($contactEmail) {
            $contactEmail->update(['value' => $request->input('email')]);
        }
        $contactPhone = Contact::where('website_id', $website->id)->where('type', 'phone')->first();
        if ($contactPhone) {
            $contactPhone->update(['value' => $request->input('phone')]);
        }

        return $this->json(
            200,
            'Website updated successfully.',
            $website
        );
    }

    /**
     * Remove a website.
     */
    public function destroy(Website $website)
    {
        $website->delete();

        return $this->json(
            200,
            'Website deleted successfully.'
        );
    }
}
