<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = Lead::query();

        // Search term
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%")
                  ->orWhere('city', 'like', "%{$searchTerm}%");
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Priority filter
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        // Study level filter
        if ($request->filled('study_level') && $request->study_level !== 'all') {
            $query->where('study_level', $request->study_level);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('inquiry_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('inquiry_date', '<=', $request->date_to);
        }

        // Note: Assignment filter has been removed as the assigned_to column is obsolete

        $leads = $query->latest()->get();

        return view('leads.index', compact('leads'));
    }
}