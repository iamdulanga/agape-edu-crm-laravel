<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NotificationsSeeder extends Seeder
{
    public function run(): void
    {
        // Choose a target user for seeding notifications
        $user = User::where('username', 'counselor')->first() ?? User::first();
        if (! $user) {
            $user = User::factory()->create([
                'name' => 'Seeded Counselor',
                'username' => 'seeded_counselor',
                'email' => 'seeded_counselor@example.com',
            ]);
        }

        // Ensure we have some leads to reference
        if (Lead::count() < 6) {
            Lead::factory(6)->create();
        }

        $leads = Lead::inRandomOrder()->take(6)->get();

        // Build a few different notification payloads
        $payloads = [];

        // 1-2: FollowUpDueNotification (today)
        foreach ($leads->slice(0, 2) as $lead) {
            $payloads[] = [
                'type' => 'App\\Notifications\\FollowUpDueNotification',
                'data' => [
                    'lead_id' => $lead->id,
                    'message' => 'Follow-up is due today for lead: ' . $lead->first_name . ' ' . $lead->last_name,
                    'type' => 'follow_up_due',
                ],
            ];
        }

        // 3: FollowUpDueSoonNotification (in 3 days)
        if ($leads->count() >= 3) {
            $lead = $leads[2];
            $payloads[] = [
                'type' => 'App\\Notifications\\FollowUpDueSoonNotification',
                'data' => [
                    'lead_id' => $lead->id,
                    'message' => 'Follow-up for lead: ' . $lead->first_name . ' ' . $lead->last_name . ' is due in 3 days.',
                    'type' => 'follow_up_due_soon',
                    'days_left' => 3,
                ],
            ];
        }

        // 4-5: LeadAssignedNotification
        foreach ($leads->slice(3, 2) as $lead) {
            $payloads[] = [
                'type' => 'App\\Notifications\\LeadAssignedNotification',
                'data' => [
                    'lead_id' => $lead->id,
                    'lead_name' => $lead->full_name,
                    'message' => 'You have been assigned a new lead: ' . $lead->full_name,
                    'action_url' => url('/leads/' . $lead->id),
                ],
            ];
        }

        // 6: LeadStatusChangedNotification
        if ($leads->count() >= 6) {
            $lead = $leads[5];
            $payloads[] = [
                'type' => 'App\\Notifications\\LeadStatusChangedNotification',
                'data' => [
                    'lead_id' => $lead->id,
                    'lead_name' => $lead->full_name,
                    'message' => 'Lead status changed from new to contacted',
                    'action_url' => url('/leads/' . $lead->id),
                ],
            ];
        }

        foreach ($payloads as $payload) {
            $user->notifications()->create([
                'id' => (string) Str::uuid(),
                'type' => $payload['type'],
                'data' => $payload['data'],
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ Seeded ' . count($payloads) . ' notifications for user: ' . $user->email);
    }
}
