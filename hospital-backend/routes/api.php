<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SpecializeController;
use App\Http\Controllers\UserController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('login',[AuthController::class,'login']);

Route::get('patients',[PatientController::class,'index']);
Route::post('patients',[PatientController::class,'store']);


Route::get('specializes',[SpecializeController::class,'index']);
Route::post('specializes',[SpecializeController::class,'store']);

Route::get('doctors',[UserController::class,'index']);
Route::post('doctors',[UserController::class,'store']);

Route::get('reservations',[ReservationController::class,'index']);
Route::post('reservDoctortime',[ReservationController::class,'reservDoctortime']);


Route::middleware('auth:sanctum')->group(function (){
    Route::get('profile',[AuthController::class,'profile']);
    Route::post('logout',[AuthController::class,'logout']);
    Route::post('reservations',[ReservationController::class,'store']);
    Route::get('DoctorReservation',[ReservationController::class,'DoctorReservation']);
    Route::get('AcceptDoctorReservation',[ReservationController::class,'AcceptDoctorReservation']);
    Route::delete('deleteReservation/{id}',[ReservationController::class,'deleteReservation']);
    Route::delete('deleteacceptReservation/{id}',[ReservationController::class,'deleteacceptReservation']);
    Route::post('acceptReservation/{id}',[ReservationController::class,'acceptReservation']);


});
