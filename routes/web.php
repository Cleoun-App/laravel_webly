<?php

use Illuminate\Support\Facades\Route;
use App\Models\Masters\Building;
use App\Models\Masters\Canteen;
use App\Models\Masters\Car;
use App\Models\Masters\Driver;
use Illuminate\Support\Carbon;
use App\Models\Administrations\RentBuilding;

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

// Route::get('/', function () {
//     $data = RentBuilding::find(1);

//     dd($data);
// });

Route::get('/generate-data', function () {

    $bf = Building::factory(10)->create();
    $cf = Canteen::factory(10)->create();
    $rf = Car::factory(10)->create();
    $df = Driver::factory(10)->create();

    dd($bf, $cf, $rf, $df);
});
