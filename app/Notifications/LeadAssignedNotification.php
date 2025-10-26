<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Lead $lead) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Lead Assignment')
            ->line('You have been assigned a new lead:')
            ->line("Name: {$this->lead->full_name}")
            ->line("Email: {$this->lead->email}")
            ->line("Phone: {$this->lead->phone}")
            ->action('View Lead', route('leads.show', $this->lead))
            ->line('Please follow up with the lead as soon as possible.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->full_name,
            'message' => "You have been assigned a new lead: {$this->lead->full_name}",
            'action_url' => route('leads.show', $this->lead),
        ];
    }
}
