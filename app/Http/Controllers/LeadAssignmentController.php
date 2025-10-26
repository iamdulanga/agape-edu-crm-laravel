<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\LeadAssignedNotification;

/**
 * DEPRECATED: This controller is no longer functional as the assigned_to column
 * has been removed from the leads table. All assignment-related functionality
 * has been disabled. If assignment tracking is needed in the future, it should
 * be implemented through a separate assignment tracking table.
 */
class LeadAssignmentController extends Controller
{
    /**
     * Show assign form modal for a given lead.
     * @deprecated Assignment functionality has been removed
     */
    public function assignForm(Lead $lead)
    {
        abort(404, 'Assignment functionality has been removed');
    }

    /**
     * Assign a lead to a specific user.
     * @deprecated Assignment functionality has been removed
     */
    public function assign(Request $request, Lead $lead)
    {
        abort(404, 'Assignment functionality has been removed');
    }

    /**
     * Unassign a lead.
     * @deprecated Assignment functionality has been removed
     */
    public function unassign(Lead $lead)
    {
        abort(404, 'Assignment functionality has been removed');
    }

    /**
     * Bulk assign multiple leads to a user.
     * @deprecated Assignment functionality has been removed
     */
    public function bulkAssign(Request $request)
    {
        abort(404, 'Assignment functionality has been removed');
    }
}
