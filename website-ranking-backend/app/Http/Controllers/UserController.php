<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApplicationResponse;
    use AuthorizesRequests;

    // Method to display a list of users
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);

        $users = User::orderByDesc('id')->paginate($limit);
        return $this->json(
            200,
            'Users retrieved successfully',
            $users
        );
    }

    // Method to store a newly created user
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->json(
                400,
                $validator->errors()
            );
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->json(
            200,
            'User created successfully',
            $user,

        );
    }

    // Method to display a specific user
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->json(
                404,
                'User  not found',
            );
        }
        return $this->json(
            200,
            'User retrieved successfully',
            $user
        );
    }

    // Method to update a specific user
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->json(
                404,
                'User  not found'
            );
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $id,
            'password' => 'string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->json(
                400,
                $validator->errors()
            );
        }

        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return $this->json(
            200,
            'User updated successfully',
            $user
        );
    }

    // Method to delete a specific user
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->json(
                404,
                'User  not found'
            );
        }

        $user->delete();
        return $this->json(
            200,
            'User  deleted successfully'
        );
    }
}
