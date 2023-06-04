<?php

use Illuminate\Support\Facades\Route;
use App\Models\Masters\Building;
use App\Models\Masters\Canteen;
use App\Models\Masters\Car;
use App\Models\Masters\Driver;
use Illuminate\Support\Carbon;
use function Illuminate\Filesystem\dirname;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\HomePageController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\Master\BuildingController;
use App\Http\Controllers\Dashboard\Master\CanteenController;
use App\Http\Controllers\Dashboard\Master\CarController;
use App\Http\Controllers\Dashboard\Master\DriverController;
use App\Http\Controllers\Dashboard\Admin\RentBuildingController;
use function GuzzleHttp\json_encode;
use App\Http\Controllers\PaymentCallbackController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () { })->middleware('auth')->name('home');

Route::get('/generate-data', function () {

    $bf = Building::factory(10)->create();
    $cf = Canteen::factory(10)->create();
    $rf = Car::factory(10)->create();
    $df = Driver::factory(10)->create();

    dd($bf, $cf, $rf, $df);
});


Route::get('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'attempt_login'])->middleware('guest');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::post('transaction/payment/callback/', function() {
//     $b = Building::find(1);

//     $b->description = json_encode(request());

//     $b->save();
// });


Route::post('transaction/payment/callback/', [PaymentCallbackController::class, 'receive']);

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

Route::middleware('auth')->prefix('/dashboard')->group(function () {

    Route::get('/index', [HomePageController::class, 'index'])->name('dashboard.index');

    Route::get('/add/user', [UserController::class, 'addUserPage'])->name('addUserPage');
    Route::post('/add/user', [UserController::class, 'addUser'])->name('postUserData');

    Route::get('/users/table', [UserController::class, 'usersTable'])->name('usersTable');

    Route::get('/profile', [UserController::class, 'profilePage'])->name('profilePage');
    Route::post('/profile', [UserController::class, 'updateUserData']);

    Route::get('/profile/config', [UserController::class, 'profileConfigPage'])->name('profileConfigPage');
    Route::post('/profile/config', [UserController::class, 'profileConfigPage']);

    Route::get('/profile/change/password', [UserController::class, 'changePasswordPage'])->name('changePasswordPage');
    Route::post('/profile/change/password', [UserController::class, 'changePassword']);


    /**
     *  Master Building
     */

    Route::get('/master/building/add', [BuildingController::class, 'addBuildingPage'])->name('addBuildingPage');
    Route::post('/master/building/add', [BuildingController::class, 'addBuilding']);

    Route::get('/master/building/table', [BuildingController::class, 'buildingTablePage'])->name('buildingTablePage');

    Route::get('/master/building/edit/{id}', [BuildingController::class, 'editBuildingPage'])->name('editBuildingPage');
    Route::post('/master/building/edit/{id}', [BuildingController::class, 'editBuilding']);

    Route::get('/master/building/delete/{id}', [BuildingController::class, 'deleteBuilding'])->name('buildingDelete');


    /**
     *  Master Kantin
     */

    Route::get('/master/canteen/add', [CanteenController::class, 'addCanteenPage'])->name('addCanteenPage');
    Route::post('/master/canteen/add', [CanteenController::class, 'addCanteen']);

    Route::get('/master/canteen/table', [CanteenController::class, 'canteenTablePage'])->name('canteenTablePage');

    Route::get('/master/canteen/edit/{id}', [CanteenController::class, 'editCanteenPage'])->name('editCanteenPage');
    Route::post('/master/canteen/edit/{id}', [CanteenController::class, 'editCanteen']);

    Route::get('/master/canteen/delete/{id}', [CanteenController::class, 'deleteCanteen'])->name('canteenDelete');


    /**
     *  Master Driver(car)
     */

    Route::get('/master/car/add', [CarController::class, 'addCarPage'])->name('addCarPage');
    Route::post('/master/car/add', [CarController::class, 'addCar']);

    Route::get('/master/car/table', [CarController::class, 'carTablePage'])->name('carTablePage');

    Route::get('/master/car/edit/{id}', [CarController::class, 'editCarPage'])->name('editCarPage');
    Route::post('/master/car/edit/{id}', [CarController::class, 'editCar']);

    Route::get('/master/car/delete/{id}', [CarController::class, 'deleteCar'])->name('deleteCar');


    /**
     *  Master Driver
     */

    Route::get('/master/driver/add', [DriverController::class, 'addDriverPage'])->name('addDriverPage');
    Route::post('/master/driver/add', [DriverController::class, 'addDriver']);

    Route::get('/master/driver/edit/{id}', [DriverController::class, 'editDriverPage'])->name('editDriverPage');
    Route::post('/master/driver/edit/{id}', [DriverController::class, 'editDriver']);

    Route::get('/master/driver/delete/{id}', [DriverController::class, 'deleteDriver'])->name('deleteDriver');

    Route::get('/master/driver/table', [DriverController::class, 'driverTablePage'])->name('driverTablePage');


    /**
     *  Administrasi
     *
     *  Url administrasi penyewaan gedung
     */
    Route::get('/administration/building/rent', [RentBuildingController::class, 'rentBuilding'])->name('adm.building.rent');
    Route::get('/administration/building/transactions', [RentBuildingController::class, 'transactions'])->name('adm.building.transactions');
    Route::get('/administration/building/log/transactions', [RentBuildingController::class, 'logTransaction'])->name('adm.building.log.transactions');



});

