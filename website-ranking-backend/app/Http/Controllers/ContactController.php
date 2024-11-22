<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Display a listing of the contacts for a specific website.
     */
    public function index(Website $website)
    {
        $contacts = $website->contacts;

        return response()->json([
            'message' => 'Contacts retrieved successfully.',
            'data' => $contacts,
        ]);
    }

    /**
     * Store a newly created contact in storage.
     */
    public function store(Request $request, Website $website)
    {
        $request->validate([
            'type' => 'required|string|max:50',
            'value' => 'required|string|max:255',
        ]);

        $contact = $website->contacts()->create([
            'type' => $request->type,
            'value' => $request->value,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Contact added successfully.',
            'data' => $contact,
        ], 201);
    }

    /**
     * Remove the specified contact.
     */
    public function destroy(Contact $contact)
    {
        if ($contact->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $contact->delete();

        return response()->json([
            'message' => 'Contact deleted successfully.',
        ]);
    }
}
