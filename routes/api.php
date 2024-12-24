<?php

use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//user
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/user/check', [UserController::class, 'checkUser'])->middleware('auth:sanctum');
Route::post('/user',  [UserController::class, 'store']);
Route::get('/user{email}',  [UserController::class, 'index']);
Route::put('/user/googleid/{id}',  [UserController::class, 'updateGoogleId']);
Route::put('/user/{id}',  [UserController::class, 'update']);


//doctor

//get all doctor
Route::get('/doctor', [DoctorController::class, 'index'])->middleware('auth:sanctum');;

//store doctor
Route::post('/doctor', [DoctorController::class, 'store'])->middleware('auth:sanctum');;

//update doctor
Route::put('/doctor/{id}', [DoctorController::class, 'update'])->middleware('auth:sanctum');;

//delete doctor
Route::delete('/doctor/{id}', [DoctorController::class, 'destroy'])->middleware('auth:sanctum');;

//get active doctor
Route::get('/doctor/active', [DoctorController::class, 'getActiveDoctor'])->middleware('auth:sanctum');;

//get search doctor
Route::get('/doctor/search/', [DoctorController::class, 'getSearchDoctor'])->middleware('auth:sanctum');;

//get doctor by clinic
Route::get('/doctor/clinic/{clinic_id}', [DoctorController::class, 'getDoctorByClinic'])->middleware('auth:sanctum');;

//get doctor by specialist
Route::get('/doctor/specialist/{specialist_id}', [DoctorController::class, 'getDoctorBySpecialist'])->middleware('auth:sanctum');;

//get Doctor By id
Route::get('/doctor/{id}', [DoctorController::class, 'getById'])->middleware('auth:sanctum');;


//orders

//get all order
Route::get('/order', [OrderController::class, 'index'])->middleware('auth:sanctum');
//store order
Route::post('/order', [OrderController::class, 'store'])->middleware('auth:sanctum');
//get order history by patient
Route::get('/order/patient/{patient_id}', [OrderController::class, 'getOrderHistoryByPatient'])->middleware('auth:sanctum');
//get order history by doctor
Route::get('/order/doctor/{doctor_id}', [OrderController::class, 'getOrderHistoryByDoctor'])->middleware('auth:sanctum');
//get order clinic
Route::get('/order/clinic/{clinic_id}', [OrderController::class, 'getOrderHistoryByClinic'])->middleware('auth:sanctum');
//get clinic summary
Route::get('/order/clinic/summary/{clinic_id}', [OrderController::class, 'getSummaryByClinic'])->middleware('auth:sanctum');

//xendit callback
Route::post('/order/callback', [OrderController::class, 'handleXenditCallback']);
