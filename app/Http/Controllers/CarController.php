<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Masters\Car;
use App\Models\Masters\Driver;
use App\Models\Administrations\RentCar;
use App\Utils\ResponseFormatter;

class CarController extends Controller
{
    //



    public function api_get_car_by_id(Request $request)
    {
        try {
            $car_id = $request->id;

            $car = Car::findOrFail($car_id);

            return ResponseFormatter::success($car, "Berhasil mendapatkan data mobil");
        } catch (\Throwable $th) {
            return ResponseFormatter::error([], $th->getMessage());
        }
    }

    public function api_get_all_car(Request $request)
    {
        try {
            $cars = Car::get();

            return ResponseFormatter::success($cars, 'Data mobil berhasil di dapatkan');
        } catch (\Throwable $th) {

            return ResponseFormatter::error([], $th->getMessage());
        }
    }


    public function api_get_all_available_cars(Request $request)
    {
        try {
            $cars = Car::get();

            $_cars = [];

            foreach ($cars as $car) {
                if ($car->rent_car instanceof RentCar === false) {
                    $_cars[] = $car;
                }
            }

            return ResponseFormatter::success($_cars, 'Data Mobil berhasil di dapatkan');
        } catch (\Throwable $th) {

            return ResponseFormatter::error([], $th->getMessage());
        }
    }


    public function api_get_driver_by_id(Request $request)
    {
        try {
            $driver_id = $request->id;

            $driver = Driver::findOrFail($driver_id);

            return ResponseFormatter::success($driver, "Berhasil mendapatkan data mobil");
        } catch (\Throwable $th) {
            return ResponseFormatter::error([], $th->getMessage());
        }
    }

    public function api_get_all_driver(Request $request)
    {
        try {
            $driver = Driver::get();

            return ResponseFormatter::success($driver, 'Data driver berhasil di dapatkan');
        } catch (\Throwable $th) {

            return ResponseFormatter::error([], $th->getMessage());
        }
    }

    public function api_get_all_available_driver(Request $request)
    {
        try {
            $drivers = Driver::get();

            $_drivers = [];

            foreach ($drivers as $driver) {
                if ($driver->rent_car instanceof RentCar === false) {
                    $_drivers[] = $driver;
                }
            }

            return ResponseFormatter::success($_drivers, 'Data driver berhasil di dapatkan');
        } catch (\Throwable $th) {

            return ResponseFormatter::error([], $th->getMessage());
        }
    }
}
