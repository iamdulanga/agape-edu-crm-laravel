<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class FollowUpDueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $lead;

    public function __construct($lead)
    {
        $this->lead = $lead;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'lead_id' => $this->lead->id,
            'message' => 'Follow-up is due today for lead: ' . $this->lead->first_name . ' ' . $this->lead->last_name,
            'type' => 'follow_up_due',
        ];
    }
}
