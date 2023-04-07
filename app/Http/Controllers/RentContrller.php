<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transactions\Rental;
use Illuminate\Foundation\Auth\User;

class RentContrller extends Controller
{

    public static function api_rent(Request $request, User $user, $item_cost): Rental
    {
        $date_string = $request->end_date;
        $adm_fee = $request->adm_fee ?? 0;

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
            'cost' => $item_cost * $duration + $adm_fee,
            'note' => $request->note ?? "",
            'customer_id' => $user->id,
            'status' => 'rented',
        ]);

        return $rental;
    }
}
