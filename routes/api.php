<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\MessageGroupController;

use App\Http\Controllers\Api\GroupDetailsController;

use App\Http\Controllers\Api\MessageController;

use App\Http\Controllers\Api\MessageDetailsController;

use App\Http\Controllers\Api\ClientController;

use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\OTPSendEmailController;

use App\Http\Controllers\Api\GroupMessageListController;

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

Route::post('registration', [AdminController::class, 'register']);
Route::post('login', [AdminController::class, 'login']);





Route::middleware('auth:sanctum')->group(function () {



    Route::post('/create-message-group', [MessageGroupController::class, 'create']);
    Route::post('/update-message-group', [MessageGroupController::class, 'update']);
    Route::post('/delete-message-group', [MessageGroupController::class, 'delete']);
    Route::post('/group/list', [MessageGroupController::class, 'groupList']);
    Route::post('/group-wise-message', [MessageGroupController::class, 'groupWiseMessage']);

    Route::post('/create-group-details', [GroupDetailsController::class, 'create']);
    Route::post('/delete-group-details', [GroupDetailsController::class, 'delete']);

    Route::post('/create-message', [MessageController::class, 'store']);
    Route::post('/delete-message', [MessageController::class, 'delete']);

    Route::post('/create-message-details', [MessageDetailsController::class, 'store']);
    Route::post('/delete-message-details', [MessageDetailsController::class, 'delete']);
    Route::get('/message', [MessageGroupController::class, 'group']);

    Route::get('/client-list', [ClientController::class, 'clientList']);
    Route::post('/client/create', [ClientController::class, 'create']);
    Route::post('/client/update', [ClientController::class, 'update']);
    Route::post('/client/delete', [ClientController::class, 'delete']);


    Route::get('/profile', [ClientController::class, 'Profile']);


    Route::post('/client/group/list', [ClientController::class, 'groupList']);




    Route::get('/staff-list', [StaffController::class, 'staffList']);
    Route::post('/staff/create', [StaffController::class, 'create']);
    Route::post('/staff/update', [StaffController::class, 'update']);
    Route::post('/staff/delete', [StaffController::class, 'delete']);


    Route::post('/send-otp', [OTPSendEmailController::class, 'sendOtp']);
    Route::post('/check-otp', [OTPSendEmailController::class, 'checkOtp']);



    Route::get('/admin/group/message/list', [GroupMessageListController::class, 'adminMessageList']);
    Route::post('/client-staff/group/message/list', [GroupMessageListController::class, 'clientStaffMessageList']);





});



