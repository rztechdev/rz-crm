<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily automated WhatsApp maintenance reminders at 09:00 AM
Schedule::command('crm:send-maintenance-reminders')->dailyAt('09:00');

// Schedule daily subscription expiry check & auto-reminder at 08:00 AM
Schedule::command('crm:check-subscriptions')->dailyAt('08:00');
