<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeadUpdatedNotification;
use App\Notifications\LeadCreatedNotification;
use App\Notifications\LeadDeletedNotification;

class LeadController extends Controller
{
    public function index()
    {
        $allLeads = Lead::all();
        $query = Lead::latest();
        $status = request('status');
        if ($status && in_array($status, ['new', 'contacted', 'qualified', 'converted', 'rejected'])) {
            $query->where('status', $status);
        }
        $leads = $query->get();
        $assignableUsers = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['counselor', 'manager']);
        })->get();

        return view('leads.index', compact('leads', 'assignableUsers', 'allLeads'));
    }

    public function create()
    {
        return view('leads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'age' => 'nullable|integer|min:1|max:100',
            'city' => 'nullable|string|max:255',
            'passport' => 'nullable|in:yes,no',
            'inquiry_date' => 'nullable|date',
            'study_level' => 'nullable|in:foundation,diploma,bachelor,master,phd',
            'priority' => 'nullable|in:very_high,high,medium,low,very_low',
            'preferred_universities' => 'nullable|string|max:1000',
            'special_notes' => 'nullable|string|max:2000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'inquiry_type' => 'required|string|max:255',
            'follow_up_date' => 'nullable|date',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        $lead = Lead::create($validated);

        // Notify all users about new lead creation
        $recipients = User::all();
        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new LeadCreatedNotification($lead, $request->user()));
        }

        return redirect()->route('leads.index')
            ->with('success', 'Lead created successfully!');
    }

    public function show(Lead $lead)
    {
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        return view('leads.edit', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'age' => 'nullable|integer|min:1|max:100',
            'city' => 'nullable|string|max:255',
            'passport' => 'nullable|in:yes,no',
            'inquiry_date' => 'nullable|date',
            'study_level' => 'nullable|in:foundation,diploma,bachelor,master,phd',
            'priority' => 'nullable|in:very_high,high,medium,low,very_low',
            'preferred_universities' => 'nullable|string|max:1000',
            'special_notes' => 'nullable|string|max:2000',
            'status' => 'nullable|in:new,contacted,qualified,converted,rejected',
            'inquiry_type' => 'required|string|max:255',
            'follow_up_date' => 'nullable|date',
        ]);

        // Capture original state before update
        $original = $lead->getOriginal();
        $lead->update($validated);

        // Build a diff of changed fields for notification
        $changes = [];
        foreach ($validated as $field => $newValue) {
            // Normalize dates and numbers for comparison/display
            $oldValue = $original[$field] ?? null;
            if ($oldValue instanceof \DateTimeInterface) {
                $oldValue = $oldValue->format('Y-m-d');
            }
            if ($newValue instanceof \DateTimeInterface) {
                $newValue = $newValue->format('Y-m-d');
            }
            if ($oldValue != $newValue) {
                $changes[$field] = ['old' => $oldValue, 'new' => $newValue];
            }
        }

        if (!empty($changes)) {
            $recipients = User::all(); // everyone sees the same notifications
            if ($recipients->isNotEmpty()) {
                Notification::send($recipients, new LeadUpdatedNotification($lead, $request->user(), $changes));
            }
        }

        return redirect()->route('leads.index')
            ->with('success', 'Lead updated successfully!');
    }

    public function destroy(Lead $lead)
    {
        $actor = request()->user();
        $leadName = $lead->full_name;
        $leadId = $lead->id;

        // Delete first
        $lead->delete();

        // Notify all users that a lead was deleted
        $recipients = User::all();
        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new LeadDeletedNotification($leadName, $actor, $leadId));
        }

        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully!');
    }

    // Update the updateStatus method:
    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,qualified,converted,rejected',
        ]);

    $oldStatus = $lead->status;
    $lead->update(['status' => $request->status]);

        // Log status change - FIXED: use auth()->id() directly
        Activity::create([
            'user_id' => $request->user()->id, // This works in controller methods
            'lead_id' => $lead->id,
            'action' => 'status_changed',
            'description' => "Status changed from {$oldStatus} to {$request->status}",
            'metadata' => ['old_status' => $oldStatus, 'new_status' => $request->status],
        ]);

        // Broadcast a unified update notification (status change)
        $recipients = User::all();
        if ($recipients->isNotEmpty()) {
            $changes = ['status' => ['old' => (string) $oldStatus, 'new' => (string) $request->status]];
            Notification::send($recipients, new LeadUpdatedNotification($lead, $request->user(), $changes));
        }

        return redirect()->route('leads.index')
            ->with('success', "Lead status updated to {$request->status}!");
    }

    // Update the bulkStatusUpdate method:
    public function bulkStatusUpdate(Request $request)
    {
        $request->validate([
            'lead_ids' => 'required|array',
            'lead_ids.*' => 'exists:leads,id',
            'status' => 'required|in:new,contacted,qualified,converted,rejected',
        ]);

        $updatedCount = 0;

        foreach ($request->lead_ids as $leadId) {
            $lead = Lead::find($leadId);
            if ($lead) {
                $oldStatus = $lead->status;
                $lead->update(['status' => $request->status]);

                Activity::create([
                    'user_id' => $request->user()->id, // This works in controller methods
                    'lead_id' => $lead->id,
                    'action' => 'status_changed',
                    'description' => "Status changed from {$oldStatus} to {$request->status}",
                    'metadata' => ['old_status' => $oldStatus, 'new_status' => $request->status],
                ]);

                // Broadcast unified update notification for each changed lead
                $recipients = User::all();
                if ($recipients->isNotEmpty()) {
                    $changes = ['status' => ['old' => (string) $oldStatus, 'new' => (string) $request->status]];
                    Notification::send($recipients, new LeadUpdatedNotification($lead, $request->user(), $changes));
                }

                $updatedCount++;
            }
        }

        return redirect()->route('leads.index')
            ->with('success', "{$updatedCount} leads status updated to {$request->status}!");
    }
}
