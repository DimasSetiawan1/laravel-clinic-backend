<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ChangeStatusOrderExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change-status-order-expired';
    protected $description = 'Change the status of expired orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        Order::where('status', '!=', 'PAID')
            ->where('status', '!=', 'PENDING')
            ->where('created_at', '<=', $now->subHours(24))
            ->update(['status' => 'EXPIRED']);
    }
}
