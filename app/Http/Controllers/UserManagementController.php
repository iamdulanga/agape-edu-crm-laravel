<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        // Authorization check
        $this->authorize('viewAny', User::class);

        $users = User::with('roles')->get();

        return view('users.index', compact('users'));
    }
}
