<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    /**
     * Determine if the user can view any leads.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view leads
        return true;
    }

    /**
     * Determine if the user can view the lead.
     */
    public function view(User $user, Lead $lead): bool
    {
        // All authenticated users can view individual leads
        return true;
    }

    /**
     * Determine if the user can create leads.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create leads
        return true;
    }

    /**
     * Determine if the user can update the lead.
     */
    public function update(User $user, Lead $lead): bool
    {
        // Owners and Managers can update any lead
        // Counselors can also update leads (as per business requirements)
        return $user->hasRole('owner')
            || $user->hasRole('manager')
            || $user->hasRole('counselor');
    }

    /**
     * Determine if the user can delete the lead.
     */
    public function delete(User $user, Lead $lead): bool
    {
        // Only Owners and Managers can delete leads
        return $user->hasRole('owner') || $user->hasRole('manager');
    }

    /**
     * Determine if the user can export leads.
     */
    public function export(User $user): bool
    {
        // Only Owners and Managers can export leads
        return $user->hasRole('owner') || $user->hasRole('manager');
    }

    /**
     * Determine if the user can bulk update leads.
     */
    public function bulkUpdate(User $user): bool
    {
        // Only Owners and Managers can perform bulk updates
        return $user->hasRole('owner') || $user->hasRole('manager');
    }
}
