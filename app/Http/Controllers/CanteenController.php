<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Masters\Canteen;
use App\Models\Administrations\RentCar;
use App\Models\Administrations\RentCanteen;
use App\Utils\ResponseFormatter;

class CanteenController extends Controller
{

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
                case "CANTEEN":
                    $where_clause = "canteen_id";
                    break;
                case "RENTAL":
                    $where_clause = "rent_id";
                    break;
                default:
                    throw new \Exception("Parameter '$field' yang anda masukan tidak ter-indentifikasi");
                    break;
            }

            $result = RentCanteen::where([$where_clause => $id])->get();

            if ($result === false or empty($result) or count($result) === 0)
                throw new \Exception("Tidak ada data yang di dapatkan");

            return ResponseFormatter::success($result, 'Berhasil mendapatkan data sewa gedung');

            // ...
        } catch (\Throwable $th) {
            return ResponseFormatter::error([], $th->getMessage());
        }
    }

    public function api_rent_canteen(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $ct_id = $request->ct_id;
            $driver_id = $request->driver_id;

            $is_rented = RentCanteen::isRented($ct_id);

            if ($is_rented) {
                throw new \Exception('Cantten sedang di-sewakan');
            }

            if (empty($user_id) || empty($ct_id)) {
                throw new \Exception('Harap masukan id user dan id kantin');
            }

            $canteen = Canteen::find($ct_id);

            if ($canteen instanceof Canteen === false)
                throw new \Exception('Kantin tidak di temukan!');

            $user = User::find($user_id);

            if ($user instanceof User === false)
                throw new \Exception('Pengguna tidak di temukan!');

            $rental = RentContrller::api_rent($request, $user, $canteen->price);

            $rent_data = RentCanteen::create([
                'rent_id' => $rental->id,
                'canteen_id' => $canteen->id,
                'user_id' => $user->id,
            ]);

            $rent_data->canteen = $canteen;
            $rent_data->rent = $rental;

            return ResponseFormatter::success($rent_data, 'Mobil berhasil di-sewakan');
        } catch (\Throwable $th) {
            return ResponseFormatter::error([], $th->getMessage());
        }
    }

    public function api_get_all_available_canteens(Request $request)
    {
        try {
            $canteens = Canteen::get()->all();

            $_canteens = [];

            foreach ($canteens as $ct) {
                if ($ct->rent_car instanceof RentCar === false) {
                    $_canteens[] = $ct;
                }
            }

            return ResponseFormatter::success($_canteens, 'Data driver berhasil di dapatkan');
        } catch (\Throwable $th) {

            return ResponseFormatter::error([], $th->getMessage());
        }
    }
}
