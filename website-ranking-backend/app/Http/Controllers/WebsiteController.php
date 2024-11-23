<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationResponse;
use App\Models\Website;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class WebsiteController extends Controller
{
    use ApplicationResponse;

    /**
     * Display a listing of websites.
     */
    public function index(Request $request)
    {
        $websites = Website::where('domain', 'LIKE', '%' . $request->input('search', '') . '%')
            ->orderBy('rank', 'asc')
            ->paginate(50);

        return $this->json(
            200,
            'Websites retrieved successfully.',
            $websites
        );
    }

    public function byName($name) {
        $website = Website::with('contacts')->where('name', $name)->first();
        if (!$website) {
            return $this->json(
                404,
                'Website not found.',
                null
            );
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
