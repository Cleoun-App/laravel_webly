<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Masters\Canteen;
use App\Models\Administrations\RentCar;
use App\Models\Administrations\RentCanteen;
use App\Utils\ResponseFormatter;
use App\Models\Logger\RentalLog;
use App\Models\Transactions\Rental;
use App\Models\Transactions\Order;

class CanteenController extends Controller
{

/**
     *
     *  Metode untuk membooking gedung
     *
     *  @param Request $request
     *  parameter request
     *  canteen_id, renter_id(id user yg menyewa), start_date,
     *  end_date, note(catatan optional)
     *  @return Order $order
     *  mengembalikan data order jika berhasil
     *
     */
    public function api_booking_canteen(Request $request)
    {
        try {
            DB::beginTransaction();

            $canteen = Canteen::findOrFail($request->canteen_id);
            $customer = \App\Models\User::findOrFail($request->renter_id);

            if($canteen->isFree() === false) {
                throw new \Exception('Kantin telah di-sewakan harap pilih gedung lain!');
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
            $tax_fee = $this->hitungPajak($canteen->price * $real_duration);

            $rent_canteen = new RentCanteen();
            $trx_rental = new Rental();
            $order = new Order();

            $trx_rental->start_date = $start_date;
            $trx_rental->end_date = $end_date;
            $trx_rental->duration = $real_duration;
            $trx_rental->cost = $canteen->price * $real_duration + $adm_fee + $tax_fee;
            $trx_rental->note = $request->note ?? '';
            $trx_rental->customer_id = $customer->id;

            $trx_rental->save();

            $order->key = uniqid(time());
            $order->type = 'rent_canteen';
            $order->total_price = $canteen->price * $real_duration + $adm_fee + $tax_fee;;
            $order->transaction()->associate($trx_rental);
            $order->user()->associate($customer);

            $trx_data['order_id'] = $order->key;
            $trx_data['gross_amount'] = $canteen->price * $real_duration;
            $trx_data['tax_fee'] = $tax_fee;
            $trx_data['adm_fee'] = $adm_fee;
            $trx_data['data'] = [
                'trx_type' => 'rental',
                'rent_start' => $start_date,
                'rent_end' => $end_date,
                'duration' => $format_duration,
            ];

            $item_data['name'] = $canteen->name;
            $item_data['price'] = $canteen->price;
            $item_data['duration'] = $real_duration;
            $item_data['data'] = [];

            $user_data['name'] = $customer->name;
            $user_data['email'] = $customer->email;
            $user_data['phone'] = $customer->nomor_telp;

            $snap_token = $this->createSnapToken($trx_data, $item_data, $user_data);

            // update snap token
            $order->snap_token = $snap_token;

            $rent_canteen->rent()->associate($trx_rental);
            $rent_canteen->user()->associate($customer);
            $rent_canteen->canteen()->associate($canteen);

            $rent_canteen->save();

            $data_order =   [
                'rent_data' => [
                    'rent_id' => $rent_canteen->id,
                    'rent_model' => get_class($rent_canteen),
                    'trx_name' => "Penyewaan Gedung " . $canteen->name,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'duration' => $real_duration,
                    'item_id' => $canteen->id,
                    'item_model' => get_class($canteen),
                    'item_name' => $canteen->name,
                    'item_price' => $canteen->price,
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

            return ResponseFormatter::success($order, 'Kantin berhasil di-booking');
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

            return ResponseFormatter::success($rent_data, 'Kantin berhasil di-sewakan');
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

            return ResponseFormatter::success($_canteens, 'Data kantin berhasil di dapatkan');
        } catch (\Throwable $th) {

            return ResponseFormatter::error([], $th->getMessage());
        }
    }
}
