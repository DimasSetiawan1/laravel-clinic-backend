<?php

use App\Console\Commands\ChangeStatusAgora;
use App\Console\Commands\ChangeStatusOrderExpired;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('app:change-status-agora', function (){
    \Log::info('Changing status of Agora rooms...');
    $this->call(ChangeStatusAgora::class);
    $this->info('Agora rooms status changed successfully.');
})->everyMinute();
