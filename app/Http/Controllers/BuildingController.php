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
                case "BUILDING":
                    $where_clause = "building_id";
                    break;
                case "RENTAL":
                    $where_clause = "rent_id";
                    break;
                default:
                    throw new \Exception("Parameter '$field' yang anda masukan tidak ter-indentifikasi");
                    break;
            }

            $result = RentBuilding::where([$where_clause => $id])->get();

            if ($result === false or empty($result) or count($result) === 0)
                throw new \Exception("Tidak ada data yang di dapatkan");

            return ResponseFormatter::success($result, 'Berhasil mendapatkan data sewa gedung');

            // ...
        } catch (\Throwable $th) {
            return ResponseFormatter::error([], $th->getMessage());
        }
    }

    public function api_pay_rent(Request $request)
    {
        try {
            $payment_data = $request->payment_data;
            $payment_method = $request->payment_method;
            $rental_id = $request->rental_id;
            $penalty = $request->penalty;
            $adm_fee = $request->adm_fee;
            $cost = $request->cost;

            $total_payment = $cost + $adm_fee + $penalty;

            $rental = Rental::findOrFail($rental_id);

            $rent_building = RentBuilding::where('rent_id', $rental->id)->first();

            $user = $rent_building->user;

            $building = $rent_building->building;

            $status = 'success';

            if ($total_payment < $rental->cost) {
                $status = "pending";
            }

            $rental->update([
                'payment_data' => $payment_data,
                'payment_method' => $payment_method,
                'payment_date' => Carbon::now(),
                'total_payment' => $total_payment,
                'status' => $status,
            ]);

            return ResponseFormatter::success([
                'title' =>  'Transaksi Pembayaran Penyewaan Gedung',
                'item' => $building->name,
                'item_desc' => $building->description,
                'item_cost' => $building->price,
                'adm_fee' => $adm_fee,
                'penalty' => $penalty,
                'total_payment' => $total_payment,
                'actual_cost' => $rental->cost,
                'customer_name' => $user->name,
            ], 'Pembayaran berhasil di lakukan');
        } catch (\Throwable $th) {
            return ResponseFormatter::error([], $th->getMessage());
        }
    }

    public function api_rent_building(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $building_id = $request->building_id;

            $is_rented = RentBuilding::isBuildingRented($building_id);

            if ($is_rented) {
                throw new \Exception('Gedung sudah di-sewakan');
            }

            if (empty($user_id) || empty($building_id)) {
                throw new \Exception('Harap masukan id user dan id gedung');
            }

            // Pastikan user id dan building id ada
            $user = User::find($user_id);
            $building = Building::find($building_id);

            $rental =  RentContrller::api_rent($request, $user, $building->price);

            $rent_data = RentBuilding::create([
                'rent_id' => $rental->id,
                'building_id' => $building->id,
                'user_id' => $user->id,
            ]);

            $rent_data->building = $building;
            $rent_data->rent = $rental;

            return ResponseFormatter::success($rent_data, 'Gedung berhasil di-sewa');
        } catch (\Throwable $th) {
            return ResponseFormatter::error([], $th->getMessage());
        }
    }

    public function api_delete_rented_building($rb_id)
    {
        try {

            $rent_building = RentBuilding::findOrFail($rb_id);

            $trx_data = $rent_building->rent;

            $rent_building->delete();

            if ($trx_data instanceof Rental) {
                $trx_data->delete();
            }

            return ResponseFormatter::success([], "Data dengan parameter '$rb_id' berhasil di hapus");
        } catch (\Throwable $th) {
            return ResponseFormatter::error([], $th->getMessage());
        }
    }

    public function api_get_building_by_id(Request $request)
    {
        try {
            $building_id = $request->id;

            $building = Building::findOrFail($building_id);

            return ResponseFormatter::success($building, "Berhasil mendapatkan data gedung");
        } catch (\Throwable $th) {
            return ResponseFormatter::error([], $th->getMessage());
        }
    }

    public function api_get_all_buildings()
    {
        try {
            $buildings = Building::get();

            return ResponseFormatter::success($buildings, 'Data building berhasil di dapatkan');
        } catch (\Throwable $th) {

            return ResponseFormatter::error([], $th->getMessage());
        }
    }

}
