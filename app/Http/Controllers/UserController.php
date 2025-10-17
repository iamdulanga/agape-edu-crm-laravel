<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:owner,manager,counselor',
        ]);

        // Check authorization based on current user's role
        $currentUser = $request->user();
        $requestedRole = $validated['role'];

        // Owner can create any role
        // Manager can only create manager and counselor
        if ($currentUser->hasRole('manager')) {
            if ($requestedRole === 'owner') {
                return redirect()->back()
                    ->with('error', 'Managers cannot create Owner accounts.');
            }
        }

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Attach the role
        $role = Role::where('name', $requestedRole)->first();
        if ($role) {
            $user->roles()->attach($role);
        }

        return redirect()->route('users.index')
            ->with('success', "User '{$user->name}' created successfully as {$requestedRole}!");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $currentUser = auth()->user();

        // Prevent self-deletion
        if ($currentUser->id === $user->id) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }

        // Check authorization based on current user's role
        $userRoles = $user->roles->pluck('name')->toArray();

        if ($currentUser->hasRole('manager')) {
            // Managers can only delete counselors
            if (in_array('owner', $userRoles) || in_array('manager', $userRoles)) {
                return redirect()->back()
                    ->with('error', 'Managers can only delete Counselor accounts.');
            }
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User '{$userName}' deleted successfully!");
    }
}
