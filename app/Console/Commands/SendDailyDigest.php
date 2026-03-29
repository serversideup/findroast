<?php

namespace App\Console\Commands;

use App\Models\DailyDigestSubscriber;
use App\Notifications\DailyDigest;
use Illuminate\Console\Command;
use Modules\Offering\Models\Roast;

class SendDailyDigest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'digest:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily digest emails to all subscribers with new coffees from the last 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting daily digest process...');

        // Get all roasts created in the last 24 hours
        $newRoasts = Roast::with(['company', 'countries', 'processes', 'flavorNotes'])
            ->where('created_at', '>=', now()->subDay())
            ->where('in_stock', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $roastCount = $newRoasts->count();
        $this->info("Found {$roastCount} new coffees in the last 24 hours.");

        if ($roastCount === 0) {
            $this->info('No new coffees to send. Skipping digest.');
            return Command::SUCCESS;
        }

        // Get all subscribers
        $subscribers = DailyDigestSubscriber::with('user')->get();
        $subscriberCount = $subscribers->count();
        $this->info("Found {$subscriberCount} subscribers.");

        if ($subscriberCount === 0) {
            $this->info('No subscribers found. Skipping digest.');
            return Command::SUCCESS;
        }

        // Send notification to each subscriber
        $sent = 0;
        foreach ($subscribers as $subscriber) {
            try {
                $subscriber->user->notify(new DailyDigest($newRoasts, $subscriber->unsubscribe_token));
                $sent++;
            } catch (\Exception $e) {
                $this->error("Failed to send digest to user {$subscriber->user->email}: {$e->getMessage()}");
            }
        }

        $this->info("Successfully queued {$sent} daily digest emails.");

        return Command::SUCCESS;
    }
}
