<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationResponse;
use App\Models\Contact;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    use ApplicationResponse;

    public function index(Website $website)
{
    $contacts = $website->contacts;

    return $this->json(
        200,
        'Contacts retrieved successfully.',
        $contacts
    );
}

public function store(Request $request, Website $website)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'message' => 'required|string',
    ]);

    $contact = Contact::create([
        'website_id' => $website->id,
        'name' => $request->name,
        'email' => $request->email,
        'message' => $request->message,
    ]);

    return $this->json(
        201,
        'Contact submitted successfully.',
        $contact,
    );
}
}
