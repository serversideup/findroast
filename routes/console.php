<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule daily digest emails at 9:00 AM
Schedule::command('digests:send-daily')
    ->dailyAt('09:00')
    ->timezone('America/New_York') // Adjust timezone as needed
    ->withoutOverlapping()
    ->onOneServer();
