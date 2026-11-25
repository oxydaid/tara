<?php

use App\Models\CollaborationMember;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    CollaborationMember::where('last_active_at', '<', now()->subSeconds(25))->delete();
})->everyMinute()->name('prune-inactive-members')->withoutOverlapping();
