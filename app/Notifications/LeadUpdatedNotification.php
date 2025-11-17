<?php

namespace App\Notifications;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class LeadUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Lead $lead,
        public User $actor,
        public array $changes
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function fieldLabels(): array
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'email' => 'email',
            'phone' => 'phone',
            'age' => 'age',
            'city' => 'city',
            'passport' => 'passport',
            'inquiry_date' => 'inquiry date',
            'study_level' => 'study level',
            'priority' => 'priority',
            'preferred_universities' => 'preferred universities',
            'special_notes' => 'special notes',
            'status' => 'status',
            'inquiry_type' => 'inquiry type',
            'follow_up_date' => 'follow-up date',
        ];
    }

    protected function formatValue($value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }
        return (string) $value;
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
        $oldFullName = trim(($this->changes['first_name']['old'] ?? $this->lead->first_name).' '.($this->changes['last_name']['old'] ?? $this->lead->last_name));
        $newFullName = $this->lead->full_name;

        $parts = [];

        // Special case: if first_name or last_name changed, show a name-change sentence
        if (isset($this->changes['first_name']) || isset($this->changes['last_name'])) {
            $parts[] = "the {$actorRole} {$actorName} changed {$oldFullName}'s name to {$newFullName}";
        }

        $labels = $this->fieldLabels();

        foreach ($this->changes as $field => $pair) {
            if (in_array($field, ['first_name','last_name'])) {
                continue; // already handled by special case above
            }
            $old = $this->formatValue($pair['old'] ?? null);
            $new = $this->formatValue($pair['new'] ?? null);
            $label = $labels[$field] ?? str_replace('_', ' ', $field);
            $parts[] = "the {$actorRole} {$actorName} updated {$newFullName}'s {$label} from {$old} to {$new}";
        }

        // Fallback if somehow no parts
        if (empty($parts)) {
            $parts[] = "the {$actorRole} {$actorName} updated {$newFullName}";
        }

        return implode(' | ', $parts);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->full_name,
            'actor_id' => $this->actor->id,
            'actor_name' => $this->actor->name,
            'actor_role' => $this->actorRole(),
            'changes' => $this->changes,
            'message' => $this->buildMessage(),
            'type' => 'lead_updated',
            'action_url' => route('leads.show', $this->lead),
        ];
    }
}
