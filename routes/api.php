<?php

use App\Http\Controllers\Api\AgoraRoomController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\SpecialistController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ChatRoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Agora
Route::post('/agora/generate-token', [AgoraRoomController::class, 'generateTokenCallRoom'])
    ->middleware('auth:sanctum');

//get call rooms : all, ongoing, waiting, expired, finished
Route::get('/agora/{user_id}/call-rooms', [AgoraRoomController::class, 'getCallRooms'])
    ->middleware('auth:sanctum');
//update call room status
Route::put('/agora/{id}/call-rooms/{status}', [AgoraRoomController::class, 'updateCallRoomStatus'])
    ->middleware('auth:sanctum');


//user
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::post('/user/check', [UserController::class, 'checkUser'])->middleware('auth:sanctum');
Route::post('/user',  [UserController::class, 'store']);
Route::get('/user/{email}',  [UserController::class, 'index']);
Route::put('/user/googleid/{id}',  [UserController::class, 'updateGoogleId']);
Route::put('/user/{id}',  [UserController::class, 'update']);
// login with google done
Route::post('/login/google', [UserController::class, 'loginGoogle']);

// update token one signal untuk push notif dll
Route::post('/users/{user}/update-token', [UserController::class, 'updateToken']);

Route::post('/notification/send', [NotificationController::class, 'sendNotification'])->middleware('auth:sanctum');

//doctor

//get all doctor
Route::get('/clinic/doctor', [DoctorController::class, 'index'])->middleware('auth:sanctum');

//store doctor
Route::post('/clinic/doctor', [DoctorController::class, 'store'])->middleware('auth:sanctum');

//update doctor
Route::put('/clinic/doctor/{user}', [DoctorController::class, 'update'])->middleware('auth:sanctum');

//delete doctor
Route::delete('/clinic/doctor/{user}', [DoctorController::class, 'destroy'])->middleware('auth:sanctum');

//get active doctor
Route::get('/clinic/doctor/active', [DoctorController::class, 'getActiveDoctor']);

//get search doctor
Route::get('/doctor/search/', [DoctorController::class, 'getSearchDoctor'])->middleware('auth:sanctum');

//get doctor by clinic
Route::get('/doctor/clinic/{clinic_id}', [DoctorController::class, 'getDoctorByClinic'])->middleware('auth:sanctum');

//get doctor by specialist
Route::get('/doctor/specialist/{specialist_id}', [DoctorController::class, 'getDoctorBySpecialist'])->middleware('auth:sanctum');

//get Doctor By id
Route::get('/doctor/{id}', [DoctorController::class, 'getById'])->middleware('auth:sanctum');




//orders

//get all order
Route::get('/orders', [OrderController::class, 'index'])->middleware('auth:sanctum');
//store order
Route::post('/orders', [OrderController::class, 'store'])->middleware('auth:sanctum');
//get order history by patient
Route::get('/orders/patient/{patient_id}', [OrderController::class, 'getOrderHistoryByPatient'])->middleware('auth:sanctum');
//get order history by doctor
Route::get('/orders/doctor/{doctor_id}', [OrderController::class, 'getOrderHistoryByDoctor'])->middleware('auth:sanctum');
//get order by doctor query
Route::get('/orders/doctor/{doctor_id}/{service}/{status_service}', [OrderController::class, 'getOrderByDoctorQuery'])->middleware('auth:sanctum');
//get order clinic
Route::get('/orders/clinic/{clinic_id}', [OrderController::class, 'getOrderHistory'])->middleware('auth:sanctum');
//xendit callback
Route::post('/orders/xendit-callback', [OrderController::class, 'handleXenditCallback']);

//get specialist
Route::get('/specialists', [SpecialistController::class, "index"]);

//get clinic by id
Route::get('/clinic/{id}', [DoctorController::class, "getClinicById"])->middleware('auth:sanctum');

//get doctors by clinic_id
Route::get('/clinic/doctors/{clinic_id}', [DoctorController::class, 'getDoctorByClinic'])->middleware('auth:sanctum');
