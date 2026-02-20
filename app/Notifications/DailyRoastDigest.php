<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class DailyRoastDigest extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = 60;

    protected Collection $roasts;
    protected array $filters;

    /**
     * Create a new notification instance.
     */
    public function __construct(Collection $roasts, array $filters)
    {
        $this->roasts = $roasts;
        $this->filters = $filters;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $count = $this->roasts->count();

        $message = (new MailMessage)
            ->subject("☕ {$count} New " . str('Coffee')->plural($count) . " Match Your Filters!")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Great news! We found {$count} new " . str('coffee')->plural($count) . " that match your saved filters.");

        // Show the filters that were used
        $filterDescription = $this->getFilterDescription();
        if ($filterDescription) {
            $message->line("**Your filters:** {$filterDescription}");
        }

        $message->line('---');

        // Add each roast to the email
        foreach ($this->roasts->take(10) as $roast) {
            $roastName = $roast->name;
            $companyName = $roast->company ? " by {$roast->company->name}" : '';
            $roastUrl = $roast->url . '?ref=findroast';

            // Create clickable link using markdown
            $message->line("### [{$roastName}{$companyName}]({$roastUrl})");

            $details = [];

            if ($roast->countries->isNotEmpty()) {
                $details[] = '🌍 ' . $roast->countries->pluck('name')->join(', ');
            }

            if ($roast->processes->isNotEmpty()) {
                $details[] = '⚙️ ' . $roast->processes->pluck('name')->join(', ');
            }

            if ($roast->flavorNotes->isNotEmpty()) {
                $flavorNotes = $roast->flavorNotes->take(5)->pluck('name')->join(', ');
                $details[] = '☕ ' . $flavorNotes;
            }

            if ($roast->price) {
                $details[] = '💰 ' . ($roast->currency ?? '$') . number_format($roast->price, 2);
            }

            if (!empty($details)) {
                $message->line(implode(' • ', $details));
            }

            $message->line('---');
        }

        if ($this->roasts->count() > 10) {
            $remaining = $this->roasts->count() - 10;
            $message->line("And {$remaining} more " . str('coffee')->plural($remaining) . "...");
        }

        $message->line('Visit FindRoast to explore all new coffees!')
            ->action('Browse All Coffee', url('/'))
            ->line('You can manage your notification preferences in your profile settings.')
            ->salutation('Happy brewing!');

        return $message;
    }

    /**
     * Get a human-readable description of the active filters.
     */
    protected function getFilterDescription(): string
    {
        $parts = [];

        if (!empty($this->filters['search'])) {
            $parts[] = '**Search:** "' . $this->filters['search'] . '"';
        }

        $filterTypes = [
            'countries' => 'Origins',
            'processes' => 'Processes',
            'flavor_notes' => 'Flavors',
            'varieties' => 'Varieties',
            'companies' => 'Roasters',
        ];

        foreach ($filterTypes as $key => $label) {
            if (!empty($this->filters[$key]) && is_array($this->filters[$key]) && count($this->filters[$key]) > 0) {
                $names = implode(', ', $this->filters[$key]);
                $parts[] = "**{$label}:** {$names}";
            }
        }

        return implode(' • ', $parts);
    }
}
