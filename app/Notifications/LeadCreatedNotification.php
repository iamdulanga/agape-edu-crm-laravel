<?php

namespace App\Notifications;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class LeadCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Lead $lead,
        public User $actor
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function actorRole(): string
    {
        $role = $this->actor->roles()->value('name');
        return $role ? strtolower($role) : 'user';
    }

    protected function buildMessage(): string
    {
        $actorRole = $this->actorRole();
        $actorName = $this->actor->name;
        $leadName = $this->lead->full_name;
        return "the {$actorRole} {$actorName} created a new lead {$leadName}";
    }

    public function toArray(object $notifiable): array
    {
        return [
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->full_name,
            'actor_id' => $this->actor->id,
            'actor_name' => $this->actor->name,
            'actor_role' => $this->actorRole(),
            'message' => $this->buildMessage(),
            'type' => 'lead_created',
            'action_url' => route('leads.show', $this->lead),
        ];
    }
}
