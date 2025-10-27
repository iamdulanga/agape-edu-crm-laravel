<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadSearchController extends Controller
{
    public function search(Request $request)
    {
        // Validate search parameters to prevent injection
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:all,new,contacted,qualified,converted,rejected',
            'priority' => 'nullable|in:all,very_high,high,medium,low,very_low',
            'study_level' => 'nullable|in:all,foundation,diploma,bachelor,master,phd',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = Lead::query();

        // Search term
        if ($request->filled('search')) {
            $searchTerm = $validated['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                    ->orWhere('last_name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('phone', 'like', "%{$searchTerm}%")
                    ->orWhere('city', 'like', "%{$searchTerm}%");
            });
        }

        // Status filter
        if ($request->filled('status') && $validated['status'] !== 'all') {
            $query->where('status', $validated['status']);
        }

        // Priority filter
        if ($request->filled('priority') && $validated['priority'] !== 'all') {
            $query->where('priority', $validated['priority']);
        }

        // Study level filter
        if ($request->filled('study_level') && $validated['study_level'] !== 'all') {
            $query->where('study_level', $validated['study_level']);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('inquiry_date', '>=', $validated['date_from']);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('inquiry_date', '<=', $validated['date_to']);
        }

        // Note: Assignment filter has been removed as the assigned_to column is obsolete

        $leads = $query->latest()->get();

        return view('leads.index', compact('leads'));
    }
}
