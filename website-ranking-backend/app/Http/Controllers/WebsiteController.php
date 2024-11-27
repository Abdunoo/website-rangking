<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationResponse;
use App\Models\Website;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isEmpty;

class WebsiteController extends Controller
{
    use ApplicationResponse;
    use AuthorizesRequests;

    /**
     * Display a listing of websites.
     */
    public function index(Request $request)
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

    public function byName($name) {
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
            'domain' => 'required|string|max:255|unique:websites',
            'rank' => 'required|integer',
        ]);

        $website = Website::create($request->all());

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
            'domain' => 'required|string|max:255|unique:websites,domain,' . $website->id,
            'rank' => 'required|integer',
        ]);

        $website->update($request->all());

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
