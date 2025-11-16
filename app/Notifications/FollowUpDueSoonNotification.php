<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class FollowUpDueSoonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $lead;
    public $daysLeft;

    public function __construct($lead, $daysLeft)
    {
        $this->lead = $lead;
        $this->daysLeft = $daysLeft;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'lead_id' => $this->lead->id,
            'message' => 'Follow-up for lead: ' . $this->lead->first_name . ' ' . $this->lead->last_name . ' is due in ' . $this->daysLeft . ' days.',
            'type' => 'follow_up_due_soon',
            'days_left' => $this->daysLeft,
        ];
    }
}
