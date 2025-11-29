<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Lead;
use App\Notifications\LeadUpdatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeadUpdateNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_update_triggers_notification(): void
    {
        Notification::fake();

        // Create two users to receive notifications
        $actor = User::factory()->create();
        $otherUser = User::factory()->create();

        $lead = Lead::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'city' => 'Old City',
        ]);

        $this->actingAs($actor);

        // Perform update (controller route not required; direct model update suffices for observer)
        $lead->update(['city' => 'New City']);

        // Assert notifications dispatched to all users
        Notification::assertSentTo([$actor, $otherUser], LeadUpdatedNotification::class, function ($notification) use ($lead) {
            return $notification->lead->is($lead)
                && isset($notification->changes['city'])
                && $notification->changes['city']['old'] === 'Old City'
                && $notification->changes['city']['new'] === 'New City';
        });
    }
}
