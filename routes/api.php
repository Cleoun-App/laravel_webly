<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\AuthController;

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

Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [RegistrationController::class, 'register']);

Route::post('/forgot-password', [AuthController::class, 'forgot_password']);

Route::post('/change-password', [AuthController::class, 'change_password']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/rent/building', [BuildingController::class, 'api_rent_building']);
    Route::get('/get/rented/building/{field}/{id}', [BuildingController::class, 'api_get_rent_by_param']);
    Route::post('/rent/building/pay', [BuildingController::class, 'api_pay_rent']);
    Route::delete('/delete/rented/{id}/building/', [BuildingController::class, 'api_delete_rented_building']);
    Route::get('/master/buildings', [BuildingController::class, 'api_get_all_buildings']);


});


