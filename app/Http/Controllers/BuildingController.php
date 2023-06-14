<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Masters\Building;
use App\Utils\ResponseFormatter;
use App\Models\User;
use App\Models\Administrations\RentBuilding;
use App\Models\Transactions\Rental;
use Carbon\Carbon;
use App\Models\Transactions\Order;
use App\Helpers\RentHelper;
use App\Models\Logger\RentalLog;
use Illuminate\Support\Facades\DB;

class BuildingController extends Controller
{
    use RentHelper;


    /**
     *  -------------------------------------------------
     *                      Api section
     *  -------------------------------------------------
     */



    /**
     *
     *  Metode untuk membooking gedung
     *
     *  @param Request $request
     *  parameter request
     *  building_id, renter_id(id user yg menyewa), start_date,
     *  end_date, note(catatan optional)
     *  @return Order $order
     *  mengembalikan data order jika berhasil
     *
     */
    public function api_booking_building(Request $request)
    {
        try {
            DB::beginTransaction();

            $building = Building::findOrFail($request->building_id);
            $customer = \App\Models\User::findOrFail($request->renter_id);

            if($building->isFree() === false) {
                throw new \Exception('Gedung telah di-sewakan harap pilih gedung lain!');
            }

            $start_date = $request->start_date;
            $end_date = $request->end_date;

            $result = $this->validasiRentDate($start_date, $end_date);

            if(is_array($result)) {
                throw new \Exception($result['msg']);
            }

            $h_duration = $this->hitungDurasi($start_date, $end_date);
            $real_duration = $h_duration['real'];
            $format_duration = $h_duration['format'];

            $adm_fee = config('app.adm_fee');
            $tax_fee = $this->hitungPajak($building->price * $real_duration);

            $rent_building = new RentBuilding();
            $trx_rental = new Rental();
            $order = new Order();

            $trx_rental->start_date = $start_date;
            $trx_rental->end_date = $end_date;
            $trx_rental->duration = $real_duration;
            $trx_rental->cost = $building->price * $real_duration + $adm_fee + $tax_fee;
            $trx_rental->note = $request->note ?? '';
            $trx_rental->customer_id = $customer->id;

            $trx_rental->save();

            $order->key = uniqid(time());
            $order->type = 'rent_building';
            $order->total_price = $building->price * $real_duration + $adm_fee + $tax_fee;;
            $order->transaction()->associate($trx_rental);
            $order->user()->associate($customer);

            $trx_data['order_id'] = $order->key;
            $trx_data['gross_amount'] = $building->price * $real_duration;
            $trx_data['tax_fee'] = $tax_fee;
            $trx_data['adm_fee'] = $adm_fee;
            $trx_data['data'] = [
                'trx_type' => 'rental',
                'rent_start' => $start_date,
                'rent_end' => $end_date,
                'duration' => $format_duration,
            ];

            $item_data['name'] = $building->name;
            $item_data['price'] = $building->price;
            $item_data['duration'] = $real_duration;
            $item_data['data'] = [];

            $user_data['name'] = $customer->name;
            $user_data['email'] = $customer->email;
            $user_data['phone'] = $customer->nomor_telp;

            $snap_token = $this->createSnapToken($trx_data, $item_data, $user_data);

            // update snap token
            $order->snap_token = $snap_token;

            $rent_building->rent()->associate($trx_rental);
            $rent_building->user()->associate($customer);
            $rent_building->building()->associate($building);

            $rent_building->save();

            $data_order =   [
                'rent_data' => [
                    'rent_id' => $rent_building->id,
                    'rent_model' => get_class($rent_building),
                    'trx_name' => "Penyewaan Gedung " . $building->name,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'duration' => $real_duration,
                    'item_id' => $building->id,
                    'item_model' => get_class($building),
                    'item_name' => $building->name,
                    'item_price' => $building->price,
                ],
                'customer_data' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->nomor_telp,
                ]
            ];

            $order->transaction_data = $data_order;

            $order->save();

            RentalLog::logTrx($trx_rental);

            DB::commit();

            return ResponseFormatter::success($order, 'Gedung berhasil di-booking');
        } catch (\Throwable $th) {
            DB::rollBack();

            return ResponseFormatter::error([], $th->getMessage());
        }
    }

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

    public function api_get_all_available_buildings()
    {
        try {
            $buildings = Building::getAvailableBuildings();

            return ResponseFormatter::success($buildings, 'Data gedung berhasil di dapatkan');
        } catch (\Throwable $th) {

            return ResponseFormatter::error([], $th->getMessage());
        }
    }
}
