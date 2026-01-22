<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('uptime:check')
    ->everyFifteenMinutes()
    ->name('uptime-checks-scheduler')
    ->withoutOverlapping();
