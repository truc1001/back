<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckChangePassword;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\WorkingtimeController;
use App\Http\Controllers\DayoffController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router){
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('storeWorkingtime', [WorkingtimeController::class, 'storeWorkingtime']);
    Route::post('updateWorkingtime/{id}', [WorkingtimeController::class, 'updateWorkingtime']);
    Route::get('reportWorkingtime', [WorkingtimeController::class, 'getReportWT']);
    Route::get('editWorkingtime/{id}', [WorkingtimeController::class, 'editWorkingtime']);
    Route::get('showWorkingtime', [WorkingtimeController::class, 'showWorkingtime']);
});

Route::group([
], function ($router){
    //OT api-----son.le
    Route::get('destroyOT/{id}', [OvertimeController::class, 'destroyOT']);
    Route::post('updateOT/{id}', [OvertimeController::class, 'updateOT']);
    Route::post('approveOT/{id}', [OvertimeController::class, 'approveOT']);
    Route::get('editOT/{id}', [OvertimeController::class, 'editOT']);
    Route::get('showOT/{id}', [OvertimeController::class, 'showOT']);
    Route::get('getdata0', [OvertimeController::class, 'getdata0']);
    Route::get('getdata1', [OvertimeController::class, 'getdata1']);
    Route::post('storeOT', [OvertimeController::class, 'storeOT']);
    Route::get('reportOT', [OvertimeController::class, 'getReport']);
    //check_in check_out-----hieu.tran
    Route::post('storeWorkingtime', [WorkingtimeController::class, 'storeWorkingtime']);
    Route::get('reportWorkingtime', [WorkingtimeController::class, 'getReportWT']);
    Route::post('updateWorkingtime/{id}', [WorkingtimeController::class, 'updateWorkingtime']);
    Route::get('editWorkingtime/{id}', [WorkingtimeController::class, 'editWorkingtime']);
    //day_off api-----truc-ho
    Route::get('showWorkingtime', [WorkingtimeController::class, 'showWorkingtime']);
    Route::get('/cancel-dayoff/{id}', [DayoffController::class, 'getCancel']);
    Route::post('/approved-dayoff/{id}', [DayoffController::class, 'ApprovedDayOff']);
    Route::post('/dayoff-update/{id}', [DayoffController::class, 'updateDayOff']);
    Route::get('/dayoff-show/{id}', [DayoffController::class, 'showDayOff']);
    Route::get('/dayoff-edit/{id}', [DayoffController::class, 'editDayOff']);
    Route::get('/get-data', [DayoffController::class, 'getData']);
    Route::get('/approved', [DayoffController::class, 'getApproved']);
    Route::post('/dayoff-register', [DayoffController::class, 'postDayOff']);
});

Route::post('/forget-password', [CheckChangePassword::class, 'forgetPassword']);
Route::post('/check-code', [CheckChangePassword::class, 'checkCode']);
Route::post('/change-password', [CheckChangePassword::class, 'changePassword']);
