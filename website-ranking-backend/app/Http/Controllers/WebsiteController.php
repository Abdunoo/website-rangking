<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    /**
     * Display a paginated list of websites with ranks.
     */
    public function index(Request $request)
    {
        $websites = Website::orderBy('rank', 'asc')
            ->paginate(50);

        return response()->json([
            'message' => 'Websites retrieved successfully.',
            'data' => $websites,
        ]);
    }

    /**
     * Show details of a specific website.
     */
    public function show(Website $website)
    {
        return response()->json([
            'message' => 'Website details retrieved successfully.',
            'data' => $website,
        ]);
    }

    /**
     * Update a website's details (admin-only).
     */
    public function update(Request $request, Website $website)
    {
        $this->authorize('admin'); // Pastikan pengguna adalah admin

        $request->validate([
            'domain' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:255',
            'rank' => 'sometimes|integer|min:1',
            'rank_change' => 'sometimes|integer',
        ]);

        $website->update($request->all());

        return response()->json([
            'message' => 'Website updated successfully.',
            'data' => $website,
        ]);
    }

    /**
     * Delete a website (admin-only).
     */
    public function destroy(Website $website)
    {
        $this->authorize('admin'); // Pastikan pengguna adalah admin

        $website->delete();

        return response()->json([
            'message' => 'Website deleted successfully.',
        ]);
    }
}
