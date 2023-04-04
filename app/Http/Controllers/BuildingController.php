<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Masters\Building;
use App\Utils\ResponseFormatter;
use App\Models\User;
use App\Models\Administrations\RentBuilding;
use App\Models\Transactions\Rental;
use Carbon\Carbon;

class BuildingController extends Controller
{


    /**
     *  -------------------------------------------------
     *                      Api section
     *  -------------------------------------------------
     */

    public function api_get_rent_by_id(Request $request)
    { }

    public function api_pay_rent(Request $request)
    { }

    public function api_rent_building(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $building_id = $request->building_id;

            if (empty($user_id) || empty($building_id)) {
                throw new \Exception('Harap masukan id user dan id gedung');
            }

            // Pastikan user id dan building id ada
            $user = User::find($user_id);
            $building = Building::find($building_id);

            $rental = $this->api_rent($request, $user, $building);

            $rent_data = RentBuilding::create([
                'rent_id' => $rental->id,
                'building_id' => $building->id,
            ]);

            $rent_data->building = $building;
            $rent_data->rent = $rental;

            return ResponseFormatter::success($rent_data, 'Gedung berhasil di-sewa');
        } catch (\Throwable $th) {
            return ResponseFormatter::error([], $th->getMessage());
        }
    }

    public function api_get_building_by_id(Request $request)
    { }

    public function api_get_all_buildings()
    {
        try {
            $buildings = Building::get();

            return ResponseFormatter::success($buildings, 'Data building berhasil di dapatkan');
        } catch (\Throwable $th) {

            return ResponseFormatter::error([], $th->getMessage());
        }
    }

    protected function api_rent(Request $request, User $user, Building $building): Rental
    {
        $date_string = $request->end_date;

        if (empty($date_string))
            throw new \Exception("Harap tentukan tanggal ahkir penyewaan");

        try {
            $carbon_date = Carbon::createFromFormat('d-m-Y', $date_string);
        } catch (\Throwable $th) {
            throw new \Exception('Format \'end_date\' tidak sesuai');
        }

        $start_date = Carbon::now();
        $end_date = $carbon_date;
        $duration = $end_date->diffInDays($start_date);

        if ($duration >= 30)
            throw new \Exception('Durasi penyewaan melebihi batas yang di tentukan!');

        $rental = Rental::create([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'duration' => $duration,
            'cost' => $building->price * $duration,
            'note' => $request->note ?? "",
            'customer_id' => $user->id,
            'status' => 'rented',
        ]);

        return $rental;
    }
}
