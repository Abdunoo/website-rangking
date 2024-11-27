<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationResponse;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApplicationResponse;
    use AuthorizesRequests;

    public function me() {
        $user = Auth::user();
        $user->photo = $user->photo ? Storage::url($user->photo) : null;
        return $this->json(
            200,
            'User retrieved successfully',
            $user);
    }

    public function updateProfile(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048' // Optional photo upload
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Update name if provided
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        // Update email if provided
        if ($request->has('email')) {
            $user->email = $request->email;
        }

        // Handle profile photo upload
        if ($request->hasFile('photo')) {
            // Delete existing photo if it exists
            if ($user->photo && Storage::exists($user->photo)) {
                Storage::delete($user->photo);
            }

            // Store new photo
            $photoPath = $request->file('photo')->store('profile_photos', 'public');
            $user->photo = $photoPath;
        }

        // Save the updated user
        $user->save();

        // Return success response
        return $this->json(
            200,
            'Profile updated successfully',
            $user
        );
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'credits' => 200,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->json(
            200,
            'User  registered successfully.',
            [
                'user' => $user,
                'token' => $token
            ],
        );
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $user->photo = $user->photo ? Storage::url($user->photo) : null;

        return $this->json(
            200,
            'Login successful.',
            [
                'user' => $user,
                'token' => $token
            ],
        );
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->json(
            200,
            'Logged out successfully.'
        );
    }


    /**
     * Remove profile photo
     */
    public function removeProfilePhoto()
    {
        $user = Auth::user();

        // Check if user has a photo
        if ($user->photo && Storage::exists($user->photo)) {
            // Delete the photo
            Storage::delete($user->photo);

            // Clear the photo path in the database
            $user->photo = null;
            $user->save();

            return $this->json(
                 true,
                'Profile photo removed successfully'
            );
        }

        return $this->json(
         200,
         'No profile photo to remove'
        );
    }

    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validatedData['current_password'], Auth::user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided password does not match our records.'],
            ]);
        }

        Auth::user()->update([
            'password' => Hash::make($validatedData['password']), // Hash the new password before saving
        ]);

        return response()->json([
            'message' => 'Password changed successfully.',
        ]);
    }
}
