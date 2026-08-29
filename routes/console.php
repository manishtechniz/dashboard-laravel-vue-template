<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// One worker that respects priority order (High first, then Default, then Low)
Schedule::command('queue:work --queue=high,default,low --stop-when-empty')
    ->everyMinute()
    ->withoutOverlapping();

// Run this command daily to delete tokens that expired more than 24 hours ago
Schedule::command('sanctum:prune-expired --hours=24')->daily();
