<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Notifications\DailyRoastDigest;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Company\Models\Company;
use Modules\Offering\Models\Country;
use Modules\Offering\Models\FlavorNote;
use Modules\Offering\Models\Process;
use Modules\Offering\Models\Roast;
use Modules\Offering\Models\Variety;

class SendDailyDigests extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'digests:send-daily';

    /**
     * The console command description.
     */
    protected $description = 'Send daily digest emails to users with filter subscriptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting daily digest send...');

        // Get all roasts created in the last 24 hours that are in stock
        $newRoasts = Roast::where('created_at', '>=', Carbon::now()->subDay())
            ->where('in_stock', 1)
            ->with(['company', 'countries', 'processes', 'flavorNotes', 'varieties', 'elevations'])
            ->get();

        if ($newRoasts->isEmpty()) {
            $this->info('No new roasts found in the last 24 hours.');
            return 0;
        }

        $this->info("Found {$newRoasts->count()} new roasts in the last 24 hours.");

        // Get all active subscriptions
        $subscriptions = Subscription::with('user')->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No active subscriptions found.');
            return 0;
        }

        $this->info("Processing {$subscriptions->count()} subscriptions...");

        $emailsQueued = 0;

        foreach ($subscriptions as $subscription) {
            // Filter roasts based on this subscription's criteria
            $matchingRoasts = $this->filterRoasts($newRoasts, $subscription);

            if ($matchingRoasts->isEmpty()) {
                $this->line("  ⏭  Skipped {$subscription->user->email} (no matches)");
                continue;
            }

            // Queue notification to user
            try {
                // Resolve filter IDs to actual names for display
                $filterNames = $this->resolveFilterNames($subscription);

                $subscription->user->notify((new DailyRoastDigest($matchingRoasts, $filterNames))->onQueue('emails'));
                $emailsQueued++;

                $this->info("  ✓  Queued digest for {$subscription->user->email} ({$matchingRoasts->count()} matches)");
            } catch (\Exception $e) {
                $this->error("  ✗  Failed to queue digest for {$subscription->user->email}: {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info("Successfully queued {$emailsQueued} digest emails.");
        $this->info("Emails will be processed by the queue worker.");

        return 0;
    }

    /**
     * Filter roasts based on subscription criteria.
     */
    protected function filterRoasts($roasts, Subscription $subscription)
    {
        $filtered = $roasts;

        // Apply search filter
        if (!empty($subscription->search)) {
            $search = strtolower($subscription->search);
            $filtered = $filtered->filter(function ($roast) use ($search) {
                return str_contains(strtolower($roast->name), $search)
                    || ($roast->company && str_contains(strtolower($roast->company->name), $search))
                    || $roast->countries->pluck('name')->map(fn($name) => strtolower($name))->contains(fn($name) => str_contains($name, $search))
                    || $roast->flavorNotes->pluck('name')->map(fn($name) => strtolower($name))->contains(fn($name) => str_contains($name, $search));
            });
        }

        $filters = $subscription->filters ?? [];

        // Apply country filter
        if (!empty($filters['countries'])) {
            $filtered = $filtered->filter(function ($roast) use ($filters) {
                return $roast->countries->pluck('id')->intersect($filters['countries'])->isNotEmpty();
            });
        }

        // Apply process filter
        if (!empty($filters['processes'])) {
            $filtered = $filtered->filter(function ($roast) use ($filters) {
                return $roast->processes->pluck('id')->intersect($filters['processes'])->isNotEmpty();
            });
        }

        // Apply flavor notes filter
        if (!empty($filters['flavor_notes'])) {
            $filtered = $filtered->filter(function ($roast) use ($filters) {
                return $roast->flavorNotes->pluck('id')->intersect($filters['flavor_notes'])->isNotEmpty();
            });
        }

        // Apply variety filter
        if (!empty($filters['varieties'])) {
            $filtered = $filtered->filter(function ($roast) use ($filters) {
                return $roast->varieties->pluck('id')->intersect($filters['varieties'])->isNotEmpty();
            });
        }

        // Apply company filter
        if (!empty($filters['companies'])) {
            $filtered = $filtered->filter(function ($roast) use ($filters) {
                return in_array($roast->company_id, $filters['companies']);
            });
        }

        return $filtered;
    }

    /**
     * Resolve filter IDs to actual names for email display.
     */
    protected function resolveFilterNames(Subscription $subscription): array
    {
        $filterNames = [];
        $filters = $subscription->filters ?? [];

        // Add search term
        if (!empty($subscription->search)) {
            $filterNames['search'] = $subscription->search;
        }

        // Resolve countries
        if (!empty($filters['countries'])) {
            $countries = Country::whereIn('id', $filters['countries'])->pluck('name')->toArray();
            if (!empty($countries)) {
                $filterNames['countries'] = $countries;
            }
        }

        // Resolve processes
        if (!empty($filters['processes'])) {
            $processes = Process::whereIn('id', $filters['processes'])->pluck('name')->toArray();
            if (!empty($processes)) {
                $filterNames['processes'] = $processes;
            }
        }

        // Resolve flavor notes
        if (!empty($filters['flavor_notes'])) {
            $flavorNotes = FlavorNote::whereIn('id', $filters['flavor_notes'])->pluck('name')->toArray();
            if (!empty($flavorNotes)) {
                $filterNames['flavor_notes'] = $flavorNotes;
            }
        }

        // Resolve varieties
        if (!empty($filters['varieties'])) {
            $varieties = Variety::whereIn('id', $filters['varieties'])->pluck('name')->toArray();
            if (!empty($varieties)) {
                $filterNames['varieties'] = $varieties;
            }
        }

        // Resolve companies
        if (!empty($filters['companies'])) {
            $companies = Company::whereIn('id', $filters['companies'])->pluck('name')->toArray();
            if (!empty($companies)) {
                $filterNames['companies'] = $companies;
            }
        }

        return $filterNames;
    }
}
