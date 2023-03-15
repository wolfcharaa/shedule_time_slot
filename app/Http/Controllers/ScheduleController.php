<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Service\CustomValidator;
use App\Service\TimeManagement\ScheduleService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ScheduleController extends Controller
{
    public function setGetUser(): int
    {
        return User::query()->find(1)->get()->value('id');
    }

    public function create(Request $request, CustomValidator $validator, ScheduleService $scheduleService): JsonResponse
    {
        $requestData = $validator->validate([
            'date' => ['required', 'date'],
        ]);
        if ($scheduleService->canInsertSlot()) {
            throw new UnprocessableEntityHttpException('На ' . $requestData['date'] . ' уже созданы задачи');
        }
        $schedule = new Schedule();
        $schedule->user()->associate($this->setGetUser());
        $schedule->date = Carbon::createFromFormat('Y-m-d', $requestData['date']);
        $schedule->save();
        return new JsonResponse([
            'message' => 'Новый день добавлен'
        ]);
    }


    // Выводить все слоты записанные по дате на пользователя
    public function getAllSlotsDates(Request $request, CustomValidator $validator): JsonResponse
    {
        $requestData = $validator->validate([
//            'schedule_id' => ['required', 'exists:App\Models\Schedule,id'],  //TODO Лишнее
            'date'        => ['required', 'date']
        ]);
        return new JsonResponse(Schedule::query()
            ->where('schedules.user_id', '=', $this->setGetUser())
//            ->where('schedules.id', '=', $requestData['schedule_id'])  //TODO лишнее
            ->where('schedules.date', '=', $requestData['date'])
            ->leftJoin('time_slots', 'schedules.id', '=', 'time_slots.schedule_id')
            ->select([
                'date',
                'start_time',
                'end_time'
            ])
            ->orderBy('start_time')
            ->limit(112) //Возможное количество задач за 7 дней забитых под завязку
            ->get());
    }

    //Вывод всех задач по пользователю
    public function getAllSlots(Request $request, CustomValidator $validator): JsonResponse
    {
        return new JsonResponse(Schedule::query()
            ->where('schedules.user_id', '=', $this->setGetUser())
            ->leftJoin('time_slots', 'schedules.id', '=', 'time_slots.schedule_id')
            ->select([
                'date',
                'start_time',
                'end_time'
            ])
            ->orderBy('start_time')
            ->get());
    }
}
