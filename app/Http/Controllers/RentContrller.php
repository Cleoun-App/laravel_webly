<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transactions\Rental;
use Illuminate\Foundation\Auth\User;

class RentContrller extends Controller
{

    public static function api_rent(Request $request, User $user, $total_cost): Rental
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
        $duration = $end_date->diffInDays($start_date) + 1;

        if ($end_date->isPast())
            throw new \Exception('Tanggal jatuh tempo tidak boleh melewati hari ini');

        if ($duration >= 30)
            throw new \Exception('Durasi penyewaan melebihi batas yang di tentukan!');

        $rental = Rental::create([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'duration' => $duration,
            'cost' => $total_cost,
            'note' => $request->note ?? "",
            'customer_id' => $user->id,
            'status' => 'waiting', // waiting for payment
            'waiting_end' => $start_date->addHours(6),
        ]);

        return $rental;
    }


    public function api_on_payment_success(Request $request)
    {
        try {

            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$serverKey = config('midtrans.server_key');

            $notif = new \Midtrans\Notification();

            $model = $notif->model;
            $model_id = $notif->model_id;
            $rent_id = $notif->rent_id;

            $rental = Rental::findOrFail($rent_id);

            $rent_building = $model::where('rent_id', $rental->id)->first();

            $rental->update([
                'payment_date' => Carbon::now(),
                'payment_method'=> $notif->payment_type,
                'total_payment' => $notif->gross_amount,
                'status' => "pending",
            ]);

            return ResponseFormatter::success([], 'Pembayaran berhasil di lakukan');

            // ...
        } catch (\Throwable $th) {
            return ResponseFormatter::error([], $th->getMessage());
        }
    }
}
