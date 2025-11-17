<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class LeadDeletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $leadName,
        public ?User $actor = null,
        public ?int $leadId = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function actorRole(): string
    {
        if (!$this->actor) {
            return 'user';
        }
        $role = $this->actor->roles()->value('name');
        return $role ? strtolower($role) : 'user';
    }

    protected function buildMessage(): string
    {
        $actorRole = $this->actor ? $this->actorRole() : 'user';
        $actorName = $this->actor ? $this->actor->name : 'A user';
        return "the {$actorRole} {$actorName} deleted lead {$this->leadName}";
    }

    public function toArray(object $notifiable): array
    {
        return [
            'lead_id' => $this->leadId,
            'lead_name' => $this->leadName,
            'actor_id' => $this->actor?->id,
            'actor_name' => $this->actor?->name,
            'actor_role' => $this->actorRole(),
            'message' => $this->buildMessage(),
            'type' => 'lead_deleted',
            'action_url' => route('leads.index'),
        ];
    }
}
