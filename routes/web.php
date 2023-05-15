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


Route::middleware('auth')->prefix('/dashboard')->group(function () {

    Route::get('/index', [HomePageController::class, 'index'])->name('dashboard.index');


    Route::get('/add/user', [UserController::class, 'addUserPage'])->name('addUserPage');
    Route::post('/add/user', [UserController::class, 'addUser']);



});
