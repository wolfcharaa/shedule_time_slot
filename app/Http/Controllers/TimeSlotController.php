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

    public function setGetUser(): int
    {
        return User::query()->find(1)->get()->value('id');
    }

    /**
     * @param Request $request
     * @param CustomValidator $validator
     * @param TimeSlotService $timeSlotService
     * @return JsonResponse
     */
    public function create(Request $request, CustomValidator $validator, TimeSlotService $timeSlotService): JsonResponse
    {
        $requestData = $validator->validate([
//            'user_id'     => ['required', 'exists:App\Models\User,id'],
//            'schedule_id' => ['required', 'exists:App\Models\Schedule,id'],
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
        $timeslot->user()->associate(User::query()->find($this->setGetUser()));  //TODO Додумать покрасивше, дублирование запроса
//        $timeslot->schedule()->associate(Schedule::query()->find($requestData['schedule_id']));
        $timeslot->schedule()->associate(Schedule::query()->where('date', '=', $requestData['date'])->value('id'));
//        $timeslot->schedule()->associate(Schedule::query()->find($requestData['date']));
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
        $timeSlot = TimeSlot::query()->find($id);
        $timeSlot->delete();
        return new JsonResponse([
            'message' => 'Удален успешно. По номеру ' . $id
        ]);
    }

//    public function update(Request $request, int $id, CustomValidator $validator): JsonResponse
//    {
//        $requestData = $validator->validate([
//            'user_id'    => 'required',
//            'date'       => 'required',
//            'start_time' => 'required',
//            'end_time'   => 'required',
//        ]);
//        $timeslot = TimeSlot::query()->find($id);
//        $timeslot->schedule()->associate(Schedule::query()->find($request['user_id']));
//        $timeslot->date = $requestData['date'];
//        $timeslot->start_time = $requestData['start_time'];
//        $timeslot->end_time = $requestData['end_time'];
//        $timeslot->save();
//        return new JsonResponse([
//            'message' => 'Новая задача создана'
//        ]);
//    }

}
