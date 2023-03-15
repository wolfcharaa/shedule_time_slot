<?php

namespace App\Service\TimeManagement;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;

class ScheduleService
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function canInsertSlot(): bool
    {
        $date      = $this->request['date'];
        /** @var Collection<Schedule> $allDateUser */
        $allDateUser = Schedule::query()
            ->where('date', '=', $date)
            ->orderBy('date', 'DESC')->get();
        $queryUser = Schedule::query()
            ->where('user_id', '=', 1)->get();  //TODO подумать как вставлять пользователя


        // Если пустой день
        if ($queryUser->count() === 0) {
            if ($allDateUser->count() === 0) {
                return false;
            }
        }
        // -------------------

        // Если указанный день уже существует //TODO пользователь?
        foreach ($allDateUser as $dateUser) {
            if ($dateUser == $date) {
                break;
            }
            return true;
        }
        //--------------------


        return false;
    }
}
