<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Lead $lead, public string $oldStatus, public string $newStatus) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Lead Status Updated')
            ->line("Lead status for {$this->lead->full_name} has been changed:")
            ->line("From: {$this->oldStatus}")
            ->line("To: {$this->newStatus}")
            ->action('View Lead', route('leads.show', $this->lead))
            ->line('Thank you for using our CRM!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->full_name,
            'message' => "Lead status changed from {$this->oldStatus} to {$this->newStatus}",
            'action_url' => route('leads.show', $this->lead),
        ];
    }
}
