<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Masters\Car;
use App\Models\Masters\Driver;
use App\Models\Administrations\RentCar;
use App\Utils\ResponseFormatter;
use App\Models\User;

class CarController extends Controller
{
    //


    public function api_get_rent_by_param(Request $request)
    {
        try {

            $field = \Str::upper($request->field);
            $id = $request->id;

            $where_clause = null;

            switch ($field) {
                case "USER":
                    $where_clause = "user_id";
                    break;
                case "CAR":
                    $where_clause = "car_id";
                    break;
                case "RENTAL":
                    $where_clause = "rent_id";
                    break;
                default:
                    throw new \Exception("Parameter '$field' yang anda masukan tidak ter-indentifikasi");
                    break;
            }

            $result = RentCar::where([$where_clause => $id])->get();

            if ($result === false or empty($result) or count($result) === 0)
                throw new \Exception("Tidak ada data yang di dapatkan");

            return ResponseFormatter::success($result, 'Berhasil mendapatkan data sewa gedung');

            // ...
        } catch (\Throwable $th) {
            return ResponseFormatter::error([], $th->getMessage());
        }
    }

    public function api_rent_car(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $car_id = $request->car_id;
            $driver_id = $request->driver_id;

            $is_rented = RentCar::isCarRented($car_id);

            $driver = Driver::find($driver_id);

            if ($driver instanceof Driver === false)
                throw new \Exception('Driver tidak di temukan!');

            $is_free = $driver->isFree();

            if (!$is_free)
                throw new \Exception('Driver tidak tersedia!');

            if ($is_rented) {
                throw new \Exception('Mobil sedang di-sewakan');
            }

            if (empty($user_id) || empty($car_id) || empty($driver_id)) {
                throw new \Exception('Harap masukan id user, driver dan mobil');
            }

            $car = Car::find($car_id);

            if ($car instanceof Car === false)
                throw new \Exception('Mobil tidak di temukan!');

            $user = User::find($user_id);

            if ($user instanceof User === false)
                throw new \Exception('Pengguna tidak di temukan!');

            $rental = RentContrller::api_rent($request, $user, $car->price);

            $rent_data = RentCar::create([
                'rent_id' => $rental->id,
                'car_id' => $car->id,
                'user_id' => $user->id,
                'driver_id' => $driver->id,
            ]);

            $rent_data->car = $car;
            $rent_data->rent = $rental;

            return ResponseFormatter::success($rent_data, 'Mobil berhasil di-sewakan');
        } catch (\Throwable $th) {
            return ResponseFormatter::error([], $th->getMessage());
        }
    }

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
            $drivers = Driver::get()->all();

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
