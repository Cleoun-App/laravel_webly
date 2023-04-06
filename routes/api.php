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


    /**
     *  --------------------------------------
     *          START - RENTBUILDING
     *  --------------------------------------
     */


    /**
     *  METHOD POST
     *  BODY PARAMETER:
     *      user_id, building_id, end_date(ahkir sewa), note
     *
     *  Untuk menyewakan gedung
     */
    Route::post('/rent/building', [BuildingController::class, 'api_rent_building']);

    /**
     *  METHOD GET
     *  URL PARAMETER:
     *      field(user,building,rental), id
     *
     *  Untuk mendapatkan data gedung yang di-sewa dengan parameter yang di berikan
     */
    Route::get('/get/rented/building/{field}/{id}', [BuildingController::class, 'api_get_rent_by_param']);

    /**
     *  METHOD POST
     *  BODY PARAMETER:
     *      payment_data, payment_method, rental_id, penalty
     *      adm_fee, cost
     *
     *  Untuk membayar biaya/total sewa dari gedung
     */
    Route::post('/pay/rent/building', [BuildingController::class, 'api_pay_rent']);

    /**
     *  METHOD DELETE
     *  URL PARAMETER:
     *      id
     *
     *  Untuk menghapus gedung yang di sewa pengguna
     */
    Route::delete('/delete/rented/{id}/building/', [BuildingController::class, 'api_delete_rented_building']);

    /**
     *  METHOD GET
     *  URL PARAMETER
     *      id
     *
     *  Untuk mendapatkan data master gedung
     */
    Route::get('/master/get/building/{id}', [BuildingController::class, 'api_get_building_by_id']);

    /**
     *  METHOD GET
     *
     *  Untuk mendapatkan semua data master gedung
     */
    Route::get('/master/buildings', [BuildingController::class, 'api_get_all_buildings']);


    /**
     *  --------------------------------------
     *            END - RENTBUILDING
     *  --------------------------------------
     */


});
