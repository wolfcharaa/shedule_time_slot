<?php

namespace App\Service\TimeManagement;

use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;

class TimeSlotService
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function canInsertSlot(): bool
    {
        $startTime  = $this->request['start_time'];
        $endTime    = $this->request['end_time'];
//        $scheduleId = $this->request['schedule_id'];  //TODO можно исключить за ненадобностью

        /** @var TimeSlot $leftSlot */
        $leftSlot = TimeSlot::query()
//            ->where('schedule_id', '=', $scheduleId)
            ->where('end_time', '<=', $startTime)
            ->limit(1)
            ->orderBy('end_time', 'DESC')->first();
        /** @var Collection<TimeSlot> $allSlots */
        $allSlots = TimeSlot::query()
            ->orderBy('end_time', 'ASC')->get();

        // Если пустой день
        if ($allSlots->count() === 0) {
            return true;
        }
        // -------------------

        // Если проверяемый самый левый
        if ($leftSlot === null) {
            $rightSlot = $allSlots[0];
            if ($rightSlot->start_time >= $endTime) {
                return true;
            }
            return false;
        }
        // -------------------------
        // Поиск правого относительно левого
        $rightSlot = null;
        foreach ($allSlots as $index => $slot) {
            if ($slot->id == $leftSlot->id) {
                $rightSlot = $allSlots[$index + 1] ?? null;
                break;
            }
        }
        // ---------------------------

        // Если правого нет, а есть только левый
        if ($rightSlot === null) {
            return true;
        }
        // ----------------------------

        // Если есть правый и левый
        if ($rightSlot->start_time >= $endTime) {
            return true;
        }
        return false;
    }
}
