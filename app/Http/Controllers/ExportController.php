<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class ExportController extends Controller
{
    public function exportLeads(Request $request)
    {
        // Note: The 'assigned_to' column has been removed from the leads table.
        // This export now only includes fields that exist in the current schema.
        // To maintain export field alignment, ensure any new columns added to the
        // leads table are also added to this export mapping.
        $leads = Lead::all();

        return (new FastExcel($leads))->download('leads-' . date('Y-m-d') . '.xlsx', function ($lead) {
            return [
                'ID' => $lead->id,
                'First Name' => $lead->first_name,
                'Last Name' => $lead->last_name,
                'Email' => $lead->email,
                'Phone' => $lead->phone,
                'Age' => $lead->age,
                'City' => $lead->city,
                'Passport' => $lead->passport,
                'Inquiry Date' => $lead->inquiry_date?->format('Y-m-d'),
                'Study Level' => $lead->study_level,
                'Priority' => $lead->priority,
                'Status' => $lead->status,
                'Preferred Universities' => $lead->preferred_universities,
                'Special Notes' => $lead->special_notes,
                'Created At' => $lead->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function exportFilteredLeads(Request $request)
    {
        // Note: The 'assigned_to' column has been removed from the leads table.
        // This export now only includes fields that exist in the current schema.
        // Apply the same filters as search
        $query = Lead::query();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $leads = $query->get();

        return (new FastExcel($leads))->download('filtered-leads-' . date('Y-m-d') . '.xlsx', function ($lead) {
            return [
                'ID' => $lead->id,
                'First Name' => $lead->first_name,
                'Last Name' => $lead->last_name,
                'Email' => $lead->email,
                'Phone' => $lead->phone,
                'Status' => $lead->status,
                'Priority' => $lead->priority,
                'Study Level' => $lead->study_level,
                'Inquiry Date' => $lead->inquiry_date?->format('Y-m-d'),
            ];
        });
    }
}