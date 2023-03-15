<?php

namespace App\Console\Commands;

use App\Http\Controllers\TimeSlotController;
use App\Models\Schedule;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Console\Command;

class Debug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверка';

    /**
     * Execute the console command.
     */
    public function handle(): mixed
    {
        return 0;
    }
}
