<?php

use App\Console\Commands\ChangeStatusAgora;
use App\Console\Commands\ChangeStatusOrderExpired;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('app:change-status-order-expired', function (){
    $this->call(ChangeStatusOrderExpired::class);
    $this->info('Order status changed to expired successfully.');
})->everyMinute();

Artisan::command('app:change-status-agora', function (){
    $this->call(ChangeStatusAgora::class);
    $this->info('Order status changed to expired successfully.');
})->everyMinute();
