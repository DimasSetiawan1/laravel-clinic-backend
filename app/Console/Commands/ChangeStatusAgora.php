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
        CallRoom::where('expired_token', '<=', $now->subHours(24))
            ->update(['status' => 'Close']);
        $this->info('Agora rooms status updated successfully.');
    }
}
