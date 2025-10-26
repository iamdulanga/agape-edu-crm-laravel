<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userRoles = $user->roles->pluck('name')->toArray();

        $data = [];

        if (in_array('owner', $userRoles)) {
            $data = $this->getOwnerStats();
        } elseif (in_array('manager', $userRoles)) {
            $data = $this->getManagerStats($user);
        } elseif (in_array('counselor', $userRoles)) {
            $data = $this->getCounselorStats($user);
        }

        return view('dashboard', $data);
    }

    private function getOwnerStats()
    {
        return [
            'totalLeads' => Lead::count(),
            'weeklyLeads' => Lead::where('created_at', '>=', now()->subWeek())->count(),
            'conversionRate' => $this->calculateConversionRate(),
        ];
    }

    private function getManagerStats($user)
    {
        // Note: Team assignment tracking has been removed with the assigned_to column
        // Manager stats now show overall system metrics
        return [
            'teamLeads' => Lead::count(), // All leads in system
            'assignedLeads' => 0, // Assignment feature removed
            'teamPerformance' => $this->calculateTeamPerformance(),
        ];
    }

    private function getCounselorStats($user)
    {
        // Note: Personal assignment tracking has been removed with the assigned_to column
        // Counselor stats now show overall system metrics
        return [
            'myLeads' => Lead::count(), // Show all leads
            'newAssignments' => Lead::where('created_at', '>=', now()->subDays(7))->count(),
            'followUpsToday' => Lead::where('status', 'contacted')->count(), // All contacted leads
        ];
    }

    private function calculateConversionRate()
    {
        $totalLeads = Lead::count();
        $convertedLeads = Lead::where('status', 'converted')->count();

        return $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 1) : 0;
    }

    private function calculateTeamPerformance()
    {
        // Simplified team performance calculation
        $totalLeads = Lead::count();
        $qualifiedLeads = Lead::whereIn('status', ['qualified', 'converted'])->count();

        return $totalLeads > 0 ? round(($qualifiedLeads / $totalLeads) * 100, 1) : 0;
    }
}
