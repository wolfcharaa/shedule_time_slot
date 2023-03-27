<?php

namespace App\Console\Commands;

use App\Http\Controllers\TimeSlotController;
use App\Models\Schedule;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\JsonResponse;

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
    public function handle(): int
    {
        $schedule = Schedule::query()->where('date', '=', '2023-03-08')->value('id');
        $oldTimeSlots = TimeSlot::query()->where('schedule_id', '=', $schedule)->get();
        foreach ($oldTimeSlots as $data) {
            echo $data;
        }

        return 0;
    }
}
