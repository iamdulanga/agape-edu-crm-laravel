<?php

namespace App\Observers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeadUpdatedNotification;
use App\Notifications\FollowUpDueNotification;
use App\Notifications\FollowUpDueSoonNotification;

class LeadObserver
{
    /**
     * Handle the Lead "updated" event.
     */
    public function updated(Lead $lead): void
    {
        $this->handleUpdateNotification($lead);
        $this->handleFollowUpNotifications($lead);
    }

    /**
     * Send a general update notification if any fields changed.
     */
    protected function handleUpdateNotification(Lead $lead): void
    {
        // Build changes diff: getChanges() contains only the fields actually modified.
        $rawChanges = $lead->getChanges();
        unset($rawChanges['updated_at']); // ignore timestamp noise

        if (empty($rawChanges)) {
            return; // nothing meaningful changed
        }

        $diff = [];
        foreach ($rawChanges as $field => $newValue) {
            $oldValue = $lead->getOriginal($field);
            // Normalize dates
            if ($oldValue instanceof \DateTimeInterface) {
                $oldValue = $oldValue->format('Y-m-d');
            }
            if ($newValue instanceof \DateTimeInterface) {
                $newValue = $newValue->format('Y-m-d');
            }
            // Only record if different (some casts may make them appear same after format)
            if ($oldValue != $newValue) {
                $diff[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        if (empty($diff)) {
            return; // after normalization nothing changed
        }

        // Determine actor (authenticated user if available, fallback to first user or system placeholder)
        $actor = Auth::user() ?: User::query()->first();
        if (!$actor) {
            return; // no users to attribute or notify yet (e.g., during initial seeding)
        }

        // Recipients: notify all users (mirrors existing controller behavior)
        $recipients = User::all();
        if ($recipients->isEmpty()) {
            return; // nothing to notify
        }

        Notification::send($recipients, new LeadUpdatedNotification($lead, $actor, $diff));
    }

    /**
     * Send follow-up notifications if the follow_up_date was changed.
     */
    protected function handleFollowUpNotifications(Lead $lead): void
    {
        if (!$lead->wasChanged('follow_up_date') || !$lead->follow_up_date) {
            return;
        }

        $recipients = User::all();
        if ($recipients->isEmpty()) {
            return;
        }

        $today = now()->startOfDay();
        // Ensure follow_up_date is a Carbon instance before comparison
        $followUpDate = Carbon::parse($lead->follow_up_date)->startOfDay();

        // Case 1: Date is set to today
        if ($followUpDate->isSameDay($today)) {
            Notification::send($recipients, new FollowUpDueNotification($lead));
        }
        // Case 2: Date is set to 3 days from now
        else if ($followUpDate->isSameDay($today->copy()->addDays(3))) {
            Notification::send($recipients, new FollowUpDueSoonNotification($lead, 3));
        }
    }
}
