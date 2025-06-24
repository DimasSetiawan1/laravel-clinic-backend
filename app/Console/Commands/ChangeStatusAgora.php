<?php

namespace App\Console\Commands;

use App\Models\CallRoom;
use Illuminate\Console\Command;

class ChangeStatusAgora extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change-status-agora';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change the status of Agora rooms';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        CallRoom::where('status', '!=', 'WAITING')
            ->where('status', '!=', 'ONGOING')
            ->where('expired_token', '<=', now()->subMinutes(30))
            ->update(['status' => 'EXPIRED']);
    }
}
