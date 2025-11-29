<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use App\Notifications\FollowUpDueNotification;
use App\Notifications\FollowUpDueSoonNotification;
use App\Notifications\LeadAssignedNotification;
use App\Notifications\LeadStatusChangedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_assigned_notification_is_sent_with_expected_channels(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $lead = Lead::factory()->create();

        // Act
        $user->notify(new LeadAssignedNotification($lead));

        // Assert
        Notification::assertSentTo(
            $user,
            LeadAssignedNotification::class,
            function ($notification, $channels) use ($lead) {
                // Ensure the right channels are targeted
                sort($channels);
                $expected = ['database', 'mail'];
                sort($expected);
                return $notification->lead->is($lead) && $channels === $expected;
            }
        );
    }

    public function test_lead_status_changed_notification_targets_mail_and_database(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $lead = Lead::factory()->create();

        $user->notify(new LeadStatusChangedNotification($lead, 'new', 'contacted'));

        Notification::assertSentTo(
            $user,
            LeadStatusChangedNotification::class,
            function ($notification, $channels) use ($lead) {
                sort($channels);
                $expected = ['database', 'mail'];
                sort($expected);
                return $notification->lead->is($lead) && $channels === $expected;
            }
        );
    }

    public function test_follow_up_due_notification_uses_database_and_payload_shape(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $lead = Lead::factory()->create();

        $notification = new FollowUpDueNotification($lead);

        // channel assertion via fake
        $user->notify($notification);
        Notification::assertSentTo(
            $user,
            FollowUpDueNotification::class,
            function ($notification, $channels) use ($lead) {
                return in_array('database', $channels) && $notification->lead->is($lead);
            }
        );

        // payload shape test by calling toDatabase directly
        $payload = $notification->toDatabase($user);
        $this->assertIsArray($payload);
        $this->assertArrayHasKey('lead_id', $payload);
        $this->assertArrayHasKey('message', $payload);
        $this->assertArrayHasKey('type', $payload);
        $this->assertSame($lead->id, $payload['lead_id']);
        $this->assertSame('follow_up_due', $payload['type']);
    }

    public function test_follow_up_due_soon_notification_uses_database_and_includes_days_left(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $lead = Lead::factory()->create();
        $daysLeft = 3;

        $notification = new FollowUpDueSoonNotification($lead, $daysLeft);

        $user->notify($notification);
        Notification::assertSentTo(
            $user,
            FollowUpDueSoonNotification::class,
            function ($notification, $channels) use ($lead, $daysLeft) {
                return in_array('database', $channels)
                    && $notification->lead->is($lead)
                    && $notification->daysLeft === $daysLeft;
            }
        );

        $payload = $notification->toDatabase($user);
        $this->assertIsArray($payload);
        $this->assertArrayHasKey('lead_id', $payload);
        $this->assertArrayHasKey('message', $payload);
        $this->assertArrayHasKey('type', $payload);
        $this->assertArrayHasKey('days_left', $payload);
        $this->assertSame('follow_up_due_soon', $payload['type']);
        $this->assertSame($daysLeft, $payload['days_left']);
    }
}
