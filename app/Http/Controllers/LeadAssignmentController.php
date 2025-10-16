<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\LeadAssignedNotification;

class LeadAssignmentController extends Controller
{
    /**
     * Show assign form modal for a given lead.
     */
    public function assignForm(Lead $lead)
    {
        $assignableUsers = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['counselor', 'manager']);
        })->get();

        return view('leads.partials.assign-modal', compact('lead', 'assignableUsers'));
    }

    /**
     * Assign a lead to a specific user.
     */
    public function assign(Request $request, Lead $lead)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->assigned_to);

        // Ensure Lead model supports this method
        $lead->assignTo($user, auth()->id());

        // Notify assigned user
        $user->notify(new LeadAssignedNotification($lead));

        return redirect()
            ->route('leads.index')
            ->with('success', "Lead assigned to {$user->name} successfully!");
    }

    /**
     * Unassign a lead.
     */
    public function unassign(Lead $lead)
    {
        $lead->unassign(auth()->id());

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead unassigned successfully!');
    }

    /**
     * Bulk assign multiple leads to a user.
     */
    public function bulkAssign(Request $request)
    {
        $request->validate([
            'lead_ids' => 'required|array',
            'lead_ids.*' => 'exists:leads,id',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->assigned_to);
        $assignedCount = 0;

        foreach ($request->lead_ids as $leadId) {
            $lead = Lead::find($leadId);
            if ($lead) {
                $lead->assignTo($user, auth()->id());
                $assignedCount++;
            }
        }

        return redirect()
            ->route('leads.index')
            ->with('success', "{$assignedCount} leads assigned to {$user->name}!");
    }
}
