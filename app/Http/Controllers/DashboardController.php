<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        // For manager, get team leads (all leads assigned to their team)
        // This is a simplified version - you might want to adjust based on your team structure
        return [
            'teamLeads' => Lead::count(), // All leads in system for simplicity
            'assignedLeads' => Lead::where('assigned_to', $user->id)->count(),
            'teamPerformance' => $this->calculateTeamPerformance(),
        ];
    }

    private function getCounselorStats($user)
    {
        return [
            'myLeads' => Lead::where('assigned_to', $user->id)->count(),
            'newAssignments' => Lead::where('assigned_to', $user->id)
                                ->where('created_at', '>=', now()->subDays(7))
                                ->count(),
            'followUpsToday' => Lead::where('assigned_to', $user->id)
                                ->where('status', 'contacted')
                                ->count(), // Simplified follow-up logic
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