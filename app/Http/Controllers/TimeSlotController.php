<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\TimeSlot;
use App\Models\User;
use App\Service\CustomValidator;
use App\Service\TimeManagement\TimeSlotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class TimeSlotController extends Controller
{

    /**
     * @param Request $request
     * @param CustomValidator $validator
     * @param TimeSlotService $timeSlotService
     * @return JsonResponse
     */
    public function create(Request $request, CustomValidator $validator, TimeSlotService $timeSlotService): JsonResponse
    {
        $requestData = $validator->validate([
            'date'        => ['required', 'date'],
            'start_time'  => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]|24:00$/u'],
            'end_time'    => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]|24:00$/u'],
            'title'       => ['required', 'alpha_num:ascii'],
            'description' => ['required', 'alpha_num:ascii']
        ]);
        if (!$timeSlotService->canInsertSlot()) {
            throw new UnprocessableEntityHttpException('Слот вставить нельзя');
        }
        $timeslot = new TimeSlot();
        $timeslot->user()->associate(User::getUser());  //TODO Додумать покрасивше, дублирование запроса
        $timeslot->schedule()->associate(Schedule::query()->where('date', '=', $requestData['date'])->value('id'));
        $timeslot->title = $requestData['title'];
        $timeslot->start_time = $requestData['start_time'];
        $timeslot->end_time = $requestData['end_time'];
        $timeslot->description = $requestData['description'];
        $timeslot->save();
        return new JsonResponse([
            'message' => 'Новый слот добавлен'
        ]);
    }

    public function delete(int $id): JsonResponse
    {
        $timeSlot = TimeSlot::query()->find($id, ['start_time', 'end_time']);
        $start = $timeSlot['start_time'];
        $end = $timeSlot['end_time'];
        $timeSlot->delete();
        return new JsonResponse([
            'message' => 'Успешно удалено. С индитификатором ' . $id . ' с временным промежутком от ' . $start . ' до ' . $end
        ]);

    }
    public function deleteTimes(Request $request, CustomValidator $validator): JsonResponse
    {
        $requestData = $validator->validate([
            'schedule_id' => ['required', 'exists:App\Models\Schedule,id'],
            'start_time'  => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]|24:00$/u'],
            'end_time'    => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]|24:00$/u'],
            ]);
        $timeSlot = TimeSlot::query()->where('user_id', '=', User::getUser())
            ->where('schedule_id', '=', $requestData['schedule_id'])
            ->where('start_time', '>=', $requestData['start_time'])
            ->where('end_time', '<=', $requestData['end_time']);
        $timeSlot->delete();
        return new JsonResponse([
            'message' => 'Задачи успешно удалены. С временным промежутком от ' . $requestData['start_time'] . ' до ' . $requestData['end_time']
        ]);

    }

    public function deleteAll(int $schedule_id): JsonResponse
    {
            $timeSlot = TimeSlot::query()->where('schedule_id', '=', $schedule_id);
            $timeSlot->delete();
            return new JsonResponse([
                'message' => 'Успешно удалены. С индитификатором расписания' . $schedule_id  //TODO вставить более информативные удалённые записи
            ]);
    }

}
