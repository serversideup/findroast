<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class DailyDigest extends Notification implements ShouldQueue
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
    protected string $unsubscribeToken;

    /**
     * Create a new notification instance.
     */
    public function __construct(Collection $roasts, string $unsubscribeToken)
    {
        $this->roasts = $roasts;
        $this->unsubscribeToken = $unsubscribeToken;
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

        if ($count === 0) {
            // Don't send email if no new coffees
            return (new MailMessage);
        }

        $message = (new MailMessage)
            ->subject("☕ {$count} New " . str('Coffee')->plural($count) . " Added Today!")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Great news! {$count} new " . str('coffee')->plural($count) . " " . ($count === 1 ? 'was' : 'were') . " added to FindRoast in the last 24 hours.");

        $message->line('---');

        // Add each roast to the email
        foreach ($this->roasts->take(10) as $roast) {
            $roastName = $roast->name;
            $companyName = $roast->company ? " by {$roast->company->name}" : '';
            $roastUrl = $roast->url . '?ref=findroast-daily';

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
            ->line('[Unsubscribe from daily digest](' . route('daily-digest.unsubscribe', $this->unsubscribeToken) . ')')
            ->salutation('Happy brewing!');

        return $message;
    }
}
