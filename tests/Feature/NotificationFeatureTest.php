<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\Role;
use App\Models\User;
use App\Notifications\FollowUpDueNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'counselor']);
        
        // Create a test user
        $this->user = User::factory()->create();
        $this->user->roles()->attach(Role::where('name', 'counselor')->first());
    }

    /** @test */
    public function notification_command_creates_notifications_for_leads_due_today()
    {
        Notification::fake();
        
        // Create a lead with follow_up_date today
        $leadToday = Lead::factory()->create([
            'follow_up_date' => today(),
        ]);
        
        // Create a lead with follow_up_date tomorrow (should not trigger)
        $leadTomorrow = Lead::factory()->create([
            'follow_up_date' => today()->addDay(),
        ]);
        
        // Run the notification command
        $this->artisan('notify:followup-due')->assertExitCode(0);
        
        // Assert notification was sent for today's lead
        Notification::assertSentTo(
            [$this->user],
            FollowUpDueNotification::class,
            function ($notification) use ($leadToday) {
                return $notification->lead->id === $leadToday->id;
            }
        );
    }

    /** @test */
    public function user_can_view_notifications_page()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('notifications.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('notifications.index');
        $response->assertViewHas('notifications');
    }

    /** @test */
    public function notifications_page_displays_unread_notifications()
    {
        $lead = Lead::factory()->create([
            'follow_up_date' => today(),
        ]);
        
        // Create notification for user
        $this->user->notify(new FollowUpDueNotification($lead));
        
        $this->actingAs($this->user);
        
        $response = $this->get(route('notifications.index'));
        
        $response->assertStatus(200);
        $response->assertSee($lead->first_name);
        $response->assertSee($lead->last_name);
    }

    /** @test */
    public function user_can_mark_notification_as_read_and_redirect_to_lead()
    {
        $lead = Lead::factory()->create([
            'follow_up_date' => today(),
        ]);
        
        // Create notification for user
        $this->user->notify(new FollowUpDueNotification($lead));
        $notification = $this->user->unreadNotifications->first();
        
        $this->actingAs($this->user);
        
        $response = $this->post(route('notifications.markAsRead', $notification->id));
        
        $response->assertRedirect(route('leads.show', $lead->id));
        
        // Verify notification is marked as read
        $this->assertNotNull($notification->fresh()->read_at);
    }

    /** @test */
    public function user_can_mark_all_notifications_as_read()
    {
        // Create multiple leads with follow_up_date today
        $lead1 = Lead::factory()->create(['follow_up_date' => today()]);
        $lead2 = Lead::factory()->create(['follow_up_date' => today()]);
        
        // Create notifications
        $this->user->notify(new FollowUpDueNotification($lead1));
        $this->user->notify(new FollowUpDueNotification($lead2));
        
        $this->actingAs($this->user);
        
        $this->assertEquals(2, $this->user->unreadNotifications()->count());
        
        $response = $this->post(route('notifications.markAllAsRead'));
        
        $response->assertRedirect(route('notifications.index'));
        $response->assertSessionHas('success');
        
        // Verify all notifications are marked as read
        $this->assertEquals(0, $this->user->unreadNotifications()->count());
    }

    /** @test */
    public function sidebar_shows_green_dot_when_unread_notifications_exist()
    {
        $lead = Lead::factory()->create([
            'follow_up_date' => today(),
        ]);
        
        $this->user->notify(new FollowUpDueNotification($lead));
        
        $this->actingAs($this->user);
        
        $response = $this->get(route('dashboard'));
        
        $response->assertStatus(200);
        // Check for green dot indicator
        $response->assertSee('bg-green-500');
    }

    /** @test */
    public function notifications_page_requires_authentication()
    {
        $response = $this->get(route('notifications.index'));
        
        $response->assertRedirect(route('login'));
    }
}
