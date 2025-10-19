<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = Auth::user()->load('roles');
        return view('profile.show', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->roles->first();
        
        // Validate based on role
        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

        // Only Owner and Manager can edit their own username
        if ($userRole && in_array($userRole->name, ['owner', 'manager'])) {
            $rules['username'] = ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)];
        }

        $validated = $request->validate($rules);

        // Update user fields
        $user->name = $validated['name'];
        
        // Only update username if user has permission
        if (isset($validated['username']) && $userRole && in_array($userRole->name, ['owner', 'manager'])) {
            $user->username = $validated['username'];
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Password changed successfully!');
    }

    /**
     * Upload or update the user's avatar.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile picture updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->roles->first();

        // Prevent Owner and Manager from deleting their own accounts
        if ($userRole && in_array($userRole->name, ['owner', 'manager'])) {
            return back()->with('error', 'You cannot delete your own account. Contact the system owner.');
        }

        // Confirm password before deletion
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password is incorrect.']);
        }

        // Delete avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Log out and delete user
        Auth::logout();
        $user->delete();

        return redirect()->route('login')->with('success', 'Your account has been deleted.');
    }
}
