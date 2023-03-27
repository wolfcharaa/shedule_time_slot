<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\TimeSlot;
use App\Service\CustomValidator;
use App\Service\TimeManagement\ScheduleService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ScheduleController extends Controller
{

    public function create(Request $request, CustomValidator $validator, ScheduleService $scheduleService): JsonResponse
    {
        $requestData = $validator->validate([
            'date' => ['required', 'date'],
        ]);
        if ($scheduleService->canInsertSlot()) {
            throw new UnprocessableEntityHttpException('На ' . $requestData['date'] . ' уже созданы задачи');
        }
        $schedule = new Schedule();
        $schedule->user()->associate(User::getUser());
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
            'date'        => ['required', 'date']
        ]);
        return new JsonResponse(Schedule::query()
            ->where('schedules.user_id', '=', User::getUser())
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
    public function getAllSlots(): JsonResponse
    {
        return new JsonResponse(Schedule::query()
            ->where('schedules.user_id', '=', User::getUser())
            ->leftJoin('time_slots', 'schedules.id', '=', 'time_slots.schedule_id')
            ->select([
                'date',
                'start_time',
                'end_time'
            ])
            ->orderBy('start_time')
            ->get());
    }

    public function deleteAll(int $schedule_id): JsonResponse
    {
        $timeSlot = TimeSlot::query()->where('schedule_id', '=', $schedule_id);
        $timeSlot->delete();
        $schedule = Schedule::query()->where('schedule_id', '=', $schedule_id);
        $schedule->delete();
        return new JsonResponse([
            'message' => 'Успешно удалены. С индитификатором расписания' . $schedule_id  //TODO вставить более информативные удалённые записи
        ]);
    }
    public function deleteDays(Request $request, CustomValidator $validator): JsonResponse
    {
        $requestData = $validator->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date']
        ]);
        $date = Schedule::query()
            ->where('date', '>=', $requestData['start_date'])
            ->where('date', '<=', $requestData['end_date'])
            ->pluck('id');
        foreach ($date as $id)
            TimeSlot::query()->where('schedule_id', '=', $id)->delete();
        $schedule = Schedule::query()
            ->where('date', '>=', $requestData['start_date'])
            ->where('date', '<=', $requestData['end_date']);
        $schedule->delete();
        return new JsonResponse([
            'message' => 'Успешно удалены расписания c ' . $requestData['start_date'] . ' по ' . $requestData['end_date'] //TODO вставить более информативные удалённые записи
        ]);
    }

    public function copySlotsIsNextDays(Request $request, CustomValidator $validator): JsonResponse
    {
        $requestData = $validator->validate([
            'select_date' => ['required', 'date'],
            'insert_date' => ['required', 'date']
        ]);
        $newSchedule = new Schedule();
        $newSchedule->user()->associate(User::getUser());
        $newSchedule->date = Carbon::createFromFormat('Y-m-d', $requestData['insert_date']);
        $newSchedule->save();
        $schedule = Schedule::query()->where('date', '=', $requestData['select_date'])->value('id');
        $oldTimeSlots = TimeSlot::query()->where('schedule_id', '=', $schedule)->get();
        foreach ($oldTimeSlots as $data) {
            $newTimeSlots = new TimeSlot();
            $newTimeSlots->user_id = $data['user_id'];
            $newTimeSlots->schedule_id = $data['schedule_id'];
            $newTimeSlots->title = $data['title'];
            $newTimeSlots->start_time = $data['start_time'];
            $newTimeSlots->end_time = $data['end_time'];
            $newTimeSlots->description = $data['description'];
            $newTimeSlots->save();
        }
        return new JsonResponse([
            'message' => 'Задачи перенесены'
        ]);
    }
}
