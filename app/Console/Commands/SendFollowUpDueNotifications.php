<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use App\Notifications\FollowUpDueNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class SendFollowUpDueNotifications extends Command
{
    protected $signature = 'notify:followup-due';
    protected $description = 'Send notifications for leads with follow-up date due today';

    public function handle()
    {
        $today = now()->toDateString();
        $users = User::all();

        // Notify for due today
        $leadsDueToday = Lead::whereDate('follow_up_date', $today)->get();
        foreach ($leadsDueToday as $lead) {
            Notification::send($users, new \App\Notifications\FollowUpDueNotification($lead));
        }

        // Notify for due in 3 days
        $threeDays = now()->addDays(3)->toDateString();
        $leadsDueSoon = Lead::whereDate('follow_up_date', $threeDays)->get();
        foreach ($leadsDueSoon as $lead) {
            Notification::send($users, new \App\Notifications\FollowUpDueSoonNotification($lead, 3));
        }

        $this->info('Follow-up due and due-soon notifications sent.');
        return 0;
    }
}
