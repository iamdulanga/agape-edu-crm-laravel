<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::with('assignedUser')
            ->latest()
            ->get();
        $assignableUsers = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['counselor', 'manager']);
        })->get();        

        return view('leads.index', compact('leads'));
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
        ]);

        Lead::create($validated);

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
    ]);

    $lead->update($validated);

        return redirect()->route('leads.index')
            ->with('success', 'Lead updated successfully!');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully!');
    }

    // Update the updateStatus method:
    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,qualified,converted,rejected'
        ]);

        $oldStatus = $lead->status;
        $lead->update(['status' => $request->status]);

        // Log status change - FIXED: use auth()->id() directly
        Activity::create([
            'user_id' => $request->user()->id, // This works in controller methods
            'lead_id' => $lead->id,
            'action' => 'status_changed',
            'description' => "Status changed from {$oldStatus} to {$request->status}",
            'metadata' => ['old_status' => $oldStatus, 'new_status' => $request->status]
        ]);

        return redirect()->route('leads.index')
            ->with('success', "Lead status updated to {$request->status}!");
    }

    // Update the bulkStatusUpdate method:
    public function bulkStatusUpdate(Request $request)
    {
        $request->validate([
            'lead_ids' => 'required|array',
            'lead_ids.*' => 'exists:leads,id',
            'status' => 'required|in:new,contacted,qualified,converted,rejected'
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
                    'metadata' => ['old_status' => $oldStatus, 'new_status' => $request->status]
                ]);

                $updatedCount++;
            }
        }

        return redirect()->route('leads.index')
            ->with('success', "{$updatedCount} leads status updated to {$request->status}!");
    }
}
