<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\RentContrller;

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
     *      field(cuman menerima param user,building,rental), id
     *
     *  Untuk mendapatkan data gedung yang di-sewa dengan parameter yang di berikan
     */
    Route::get('/get/rented/building/{field}/{id}', [BuildingController::class, 'api_get_rent_by_param']);

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
     *  METHOD GET
     *
     *  Untuk mendapatkan semua data master gedung yang tersedia/tidak disewa
     */
    Route::get('/master/available/buildings', [BuildingController::class, 'api_get_all_available_buildings']);


    /**
     *  --------------------------------------
     *            END - RENTBUILDING
     *  --------------------------------------
     */


    /**
     *  --------------------------------------
     *            START - RENTCAR
     *  --------------------------------------
     */

    /**
     *  METHOD POST
     *  BODY PARAMETER:
     *      user_id, car_id, driver_id,
     *      adm_fee(optional), end_date(ahkir sewa), note
     *
     *  Untuk menyewakan mobil
     */
    Route::post('/rent/car', [CarController::class, 'api_rent_car']);

    /**
     *  METHOD GET
     *  URL PARAMETER:
     *      field(cuman menerima param user,car,rental), id
     *
     *  Untuk mendapatkan data mobil yang di-sewa dengan parameter yang di berikan
     */
    Route::get('/get/rented/cars/{field}/{id}', [CarController::class, 'api_get_rent_by_param']);

    /**
     *  METHOD GET
     *  URL PARAMETER:
     *      field(cuman menerima param user,building,rental), id
     *
     *  Untuk mendapatkan data gedung yang di-sewa dengan parameter yang di berikan
     */
    Route::get('/get/rented/building/{field}/{id}', [BuildingController::class, 'api_get_rent_by_param']);

    /**
     *  METHOD GET
     *  URL PARAMETER
     *      id
     *
     *  Untuk mendapatkan data master mobil berdasarkan id mobil
     */
    Route::get('/master/get/car/{id}', [CarController::class, 'api_get_car_by_id']);

    /**
     *  METHOD GET
     *
     *  Untuk mendapatkan semua data master mobil
     */
    Route::get('/master/cars', [CarController::class, 'api_get_all_car']);

    /**
     *  METHOD GET
     *
     *  Untuk mendapatkan semua data master driver yang tersedia/tidak disewa
     */
    Route::get('/master/available/cars', [CarController::class, 'api_get_all_available_cars']);


    /**
     *  METHOD GET
     *  URL PARAMETER
     *      id
     *
     *  Untuk mendapatkan data master driver berdasarkan id
     */
    Route::get('/master/get/driver/{id}', [CarController::class, 'api_get_driver_by_id']);

    /**
     *  METHOD GET
     *
     *  Untuk mendapatkan semua data master driver
     */
    Route::get('/master/drivers', [CarController::class, 'api_get_all_driver']);

    /**
     *  METHOD GET
     *
     *  Untuk mendapatkan semua data master driver yang tersedia/tidak disewa
     */
    Route::get('/master/available/drivers', [CarController::class, 'api_get_all_available_driver']);

    /**
     *  --------------------------------------
     *            END - RENTCAR
     *  --------------------------------------
     */



     Route::post('/payment/success', [RentContrller::class, 'api_on_payment_success']);

     Route::post('/payment/failed', [RentContrller::class, 'api_on_payment_failed']);

});
