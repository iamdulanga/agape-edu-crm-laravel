<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Activity;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LeadController extends Controller
{
    public function index()
    {
        // Authorization check
        $this->authorize('viewAny', Lead::class);

        $leads = Lead::latest()
            ->get();
        $assignableUsers = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['counselor', 'manager']);
        })->get();

        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        return view('leads.create');
    }

    public function store(StoreLeadRequest $request)
    {
        // Authorization check
        $this->authorize('create', Lead::class);

        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            // Sanitize filename to prevent directory traversal
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid('lead_', true) . '.' . $extension;
            $avatarPath = $file->storeAs('avatars', $filename, 'public');
            $validated['avatar'] = $avatarPath;
        }

        Lead::create($validated);

        return redirect()->route('leads.index')
            ->with('success', 'Lead created successfully!');
    }

    public function show(Lead $lead)
    {
        // Authorization check
        $this->authorize('view', $lead);

        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        // Authorization check
        $this->authorize('update', $lead);

        return view('leads.edit', compact('lead'));
    }

    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        // Authorization check
        $this->authorize('update', $lead);

        $validated = $request->validated();

        $lead->update($validated);

        return redirect()->route('leads.index')
            ->with('success', 'Lead updated successfully!');
    }

    public function destroy(Lead $lead)
    {
        // Authorization check
        $this->authorize('delete', $lead);

        // Delete avatar if exists
        if ($lead->avatar && Storage::disk('public')->exists($lead->avatar)) {
            Storage::disk('public')->delete($lead->avatar);
        }

        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully!');
    }

    // Update the updateStatus method:
    public function updateStatus(Request $request, Lead $lead)
    {
        // Authorization check
        $this->authorize('update', $lead);

        $request->validate([
            'status' => 'required|in:new,contacted,qualified,converted,rejected',
        ]);

        $oldStatus = $lead->status;
        $lead->update(['status' => $request->status]);

        // Log status change
        Activity::create([
            'user_id' => $request->user()->id,
            'lead_id' => $lead->id,
            'action' => 'status_changed',
            'description' => "Status changed from {$oldStatus} to {$request->status}",
            'metadata' => ['old_status' => $oldStatus, 'new_status' => $request->status],
        ]);

        return redirect()->route('leads.index')
            ->with('success', "Lead status updated to {$request->status}!");
    }

    // Update the bulkStatusUpdate method:
    public function bulkStatusUpdate(Request $request)
    {
        // Authorization check
        $this->authorize('bulkUpdate', Lead::class);

        $request->validate([
            'lead_ids' => 'required|array|min:1',
            'lead_ids.*' => 'required|integer|exists:leads,id',
            'status' => 'required|in:new,contacted,qualified,converted,rejected',
        ]);

        $updatedCount = 0;

        foreach ($request->lead_ids as $leadId) {
            $lead = Lead::find($leadId);
            if ($lead) {
                $oldStatus = $lead->status;
                $lead->update(['status' => $request->status]);

                Activity::create([
                    'user_id' => $request->user()->id,
                    'lead_id' => $lead->id,
                    'action' => 'status_changed',
                    'description' => "Status changed from {$oldStatus} to {$request->status}",
                    'metadata' => ['old_status' => $oldStatus, 'new_status' => $request->status],
                ]);

                $updatedCount++;
            }
        }

        return redirect()->route('leads.index')
            ->with('success', "{$updatedCount} leads status updated to {$request->status}!");
    }
}
