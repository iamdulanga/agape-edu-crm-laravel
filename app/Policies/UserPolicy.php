<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        // Only Owners and Managers can view user list
        return $user->hasRole('owner') || $user->hasRole('manager');
    }

    /**
     * Determine if the user can view the user profile.
     */
    public function view(User $user, User $model): bool
    {
        // Users can view their own profile, or Owners/Managers can view any profile
        return $user->id === $model->id 
            || $user->hasRole('owner') 
            || $user->hasRole('manager');
    }

    /**
     * Determine if the user can create users.
     */
    public function create(User $user): bool
    {
        // Only Owners and Managers can create users
        return $user->hasRole('owner') || $user->hasRole('manager');
    }

    /**
     * Determine if the user can update the user.
     */
    public function update(User $user, User $model): bool
    {
        // Users can update their own profile
        // Owners can update anyone
        // Managers can update counselors only
        if ($user->id === $model->id) {
            return true;
        }

        if ($user->hasRole('owner')) {
            return true;
        }

        if ($user->hasRole('manager')) {
            return $model->hasRole('counselor');
        }

        return false;
    }

    /**
     * Determine if the user can delete the user.
     */
    public function delete(User $user, User $model): bool
    {
        // Users cannot delete themselves
        if ($user->id === $model->id) {
            return false;
        }

        // Owners can delete anyone except themselves
        if ($user->hasRole('owner')) {
            return !$model->hasRole('owner');
        }

        // Managers can only delete counselors
        if ($user->hasRole('manager')) {
            return $model->hasRole('counselor');
        }

        return false;
    }
}
