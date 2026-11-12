<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Warm config/route/view caches every night at midnight
Schedule::command('app:optimize-caches')->daily();

// Backups (spatie/laravel-backup): prune old archives, then take a fresh DB + storage snapshot.
// Destination disks and retention live in config/backup.php (BACKUP_* env vars).
Schedule::command('backup:clean')->dailyAt('01:30');
Schedule::command('backup:run')->dailyAt('02:00');
Schedule::command('backup:monitor')->dailyAt('08:00');

// Keep the audit log bounded (spatie/laravel-activitylog, days in config/activitylog.php)
Schedule::command('activitylog:clean')->weekly();
