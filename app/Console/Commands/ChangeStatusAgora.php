<?php

namespace App\Console\Commands;

use App\Models\CallRoom;
use Carbon\Carbon;
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
        $now = Carbon::now();
        $updated = CallRoom::where('expired_token', '<=', $now)
            ->where('status', '!=', 'Expired')
            ->update(['status' => 'Expired']);

        \Log::info("Agora rooms status updated: {$updated}");
        \Log::info('Agora rooms status updated successfully.');
    }
}
