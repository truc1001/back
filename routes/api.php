<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\WorkingtimeController;
use App\Http\Controllers\DayoffController;
use App\Http\Controllers\ReportProjectController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CheckChangePassword;
use App\Http\Controllers\SkillOfStaffController;
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
    // route OT
    // Route::post('ExportExcel', [OvertimeController::class, 'excel']);
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
    Route::post('getReport', [OvertimeController::class, 'getReport']);
    // route day_off
    Route::get('/cancel-dayoff/{id}', [DayoffController::class, 'getCancel']);
    Route::post('/approved-dayoff/{id}', [DayoffController::class, 'ApprovedDayOff']);
    Route::post('/dayoff-update/{id}', [DayoffController::class, 'updateDayOff']);
    Route::get('/dayoff-show/{id}', [DayoffController::class, 'showDayOff']);
    Route::get('/dayoff-edit/{id}', [DayoffController::class, 'editDayOff']);
    Route::get('/get-data', [DayoffController::class, 'getData']);
    Route::get('/approved', [DayoffController::class, 'getApproved']);
    Route::post('/dayoff-register', [DayoffController::class, 'postDayOff']);

});
Route::group([
], function ($router){
        Route::post('/forget-password', [CheckChangePassword::class, 'forgetPassword']);
        Route::post('/check-code', [CheckChangePassword::class, 'checkCode']);
        Route::post('/change-password', [CheckChangePassword::class, 'changePassword']);            //OT api-----son.le
         //OT---son.le
        Route::get('destroyOT/{id}', [OvertimeController::class, 'destroyOT']);
        Route::post('updateOT/{id}', [OvertimeController::class, 'updateOT']);
        Route::post('approveOT/{id}', [OvertimeController::class, 'approveOT']);
        Route::get('editOT/{id}', [OvertimeController::class, 'editOT']);
        Route::get('showOT/{id}', [OvertimeController::class, 'showOT']);
        Route::get('getdata0', [OvertimeController::class, 'getdata0']);
        Route::get('getdata1', [OvertimeController::class, 'getdata1']);
        Route::post('storeOT', [OvertimeController::class, 'storeOT']);
        Route::get('getReport', [OvertimeController::class, 'getReport']);

        //check_in check_out-----hieu.tran
        Route::post('storeWorkingtime', [WorkingtimeController::class, 'storeWorkingtime']);
        Route::get('reportWorkingtime', [WorkingtimeController::class, 'getReportWT']);
        Route::post('updateWorkingtime/{id}', [WorkingtimeController::class, 'updateWorkingtime']);
        Route::get('editWorkingtime/{id}', [WorkingtimeController::class, 'editWorkingtime']);
        Route::get('showWorkingtime', [WorkingtimeController::class, 'showWorkingtime']);
        Route::get('getManagerWorkingtime/{id}', [WorkingtimeController::class, 'getManagerWorkingtime']);
        Route::get('getWorkingtime', [WorkingtimeController::class, 'getWorkingtime']);
        Route::get('getUser', [WorkingtimeController::class, 'getUser']);

        //day_off api-----truc-ho
        Route::get('/cancel-dayoff/{id}', [DayoffController::class, 'getCancel']);
        Route::post('/approved-dayoff/{id}', [DayoffController::class, 'ApprovedDayOff']);
        Route::post('/dayoff-update/{id}', [DayoffController::class, 'updateDayOff']);
        Route::get('/dayoff-show/{id}', [DayoffController::class, 'showDayOff']);
        Route::get('/dayoff-edit/{id}', [DayoffController::class, 'editDayOff']);
        Route::get('/get-data', [DayoffController::class, 'getData']);
        Route::get('/approved', [DayoffController::class, 'getApproved']);
        Route::post('/dayoff-register', [DayoffController::class, 'postDayOff']);
        //report-project---hieu.tran & phuong.nguyen
        Route::post('/storeReportProject', [ReportProjectController::class, 'storeReportProject']);
        Route::get('getReportProject', [ReportProjectController::class, 'getReportProject']);
        Route::get('getReportProjectManager/{id}', [ReportProjectController::class, 'getReportProjectManager']);
        Route::post('updateReportProject/{id}', [ReportProjectController::class, 'updateReportProject']);
        Route::get('editReportProject/{id}', [ReportProjectController::class, 'editReportProject']);
        Route::get('showReportProject', [ReportProjectController::class, 'showReportProject']);
        Route::get('destroyReportProject/{id}', [ReportProjectController::class, 'destroyReportProject']);
        //project---hieu.tran & phuong.nguyen
        Route::post('storeProject', [ProjectController::class, 'storeProject']);
        Route::get('getProject', [ProjectController::class, 'getProject']);
        Route::post('updateProject/{id}', [ProjectController::class, 'updateProject']);
        Route::get('editProject/{id}', [ProjectController::class, 'editProject']);
        Route::get('showProject', [ProjectController::class, 'showProject']);
        Route::get('showFinishedProject', [ProjectController::class, 'showFinishedProject']);
        Route::get('destroyProject/{id}', [ProjectController::class, 'destroyProject']);
        Route::post('finishProject/{id}', [ProjectController::class, 'finishProject']);


        // Skill of Staff phase3 task1 --son.le-truc.ho--
        Route::get('showDetail/{id}', [SkillOfStaffController::class, 'showDetail']);
        Route::get('getDelete/{id}', [SkillOfStaffController::class, 'getDelete']);
        Route::get('livesearch', [SkillOfStaffController::class, 'searchSkill']);
        Route::get('showSkill', [SkillOfStaffController::class, 'show']);
        Route::get('createSkill', [SkillOfStaffController::class, 'create']);
        Route::post('storeSkill', [SkillOfStaffController::class, 'store']);
        Route::post('updateSkill/{id}', [SkillOfStaffController::class, 'update']);
        Route::get('editSkill/{id}', [SkillOfStaffController::class, 'edit']);
        // Route::get('livesearch', [SkillOfStaffController::class, 'searchSkill']);
        // Route::get('showSkill', [SkillOfStaffController::class, 'show']);
        // Route::get('createSkill', [SkillOfStaffController::class, 'create']);
        // Route::post('storeSkill', [SkillOfStaffController::class, 'store']);

});






