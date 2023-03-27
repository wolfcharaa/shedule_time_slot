<?php

use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TimeSlotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/schedule/date',                         [ScheduleController::class, 'getAllSlotsDates']);
Route::get('/schedule',                              [ScheduleController::class, 'getAllSlots']);
Route::post('/time_slot',                            [TimeSlotController::class, 'create']);
Route::post('/schedule',                             [ScheduleController::class, 'create']);
Route::post('/schedule/copy_slots',                  [ScheduleController::class, 'copySlotsIsNextDays']);
Route::delete('/time_slot/delete/{id}',              [TimeSlotController::class, 'delete']);
Route::delete('/time_slot/delete_times',             [TimeSlotController::class, 'deleteTimes']);
Route::delete('/time_slot/delete_all/{schedule_id}', [TimeSlotController::class, 'deleteAll']);
Route::delete('/schedule/delete_all/{schedule_id}',  [ScheduleController::class, 'deleteAll']);
Route::delete('/schedule/delete_days',               [ScheduleController::class, 'deleteDays']);
