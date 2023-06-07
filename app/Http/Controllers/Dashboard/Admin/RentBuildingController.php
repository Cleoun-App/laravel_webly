<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Masters\Building;
use App\Models\Administrations\RentBuilding;

class RentBuildingController extends Controller
{
    //

    public function rentBuilding(Request $request)
    {
        $data['page_title'] = "Sewa Gedung";
        $data['user'] = auth()->user();

        return view('dashboard.gedung.ds-admin-rent-building', $data);
    }

    public function transactions()
    {

        $data['page_title'] = "Tabel Transaksi";
        $data['user'] = auth()->user();
        $data['transactions'] = RentBuilding::orderBy('created_at', 'DESC')->get();

        return view('dashboard.gedung.ds-admin-transactions', $data);
    }

    public function showTrx($tr_id)
    {
        $data['page_title'] = "Detail Transaksi";
        $data['user'] = auth()->user();

        $rent_building = RentBuilding::find($tr_id);

        $data['renter'] = $rent_building->user;
        $data['building'] = $rent_building->building;
        $data['rent'] = $rent_building->rent;
        $data['order'] = $rent_building->rent->order;
        $data['payment_info'] = json_decode($rent_building->rent->order->payment_data, true);

        $data['sensor_key'] = ['merchant_id', 'order_id', 'signature_key', 'transaction_id', 'fraud_status'];

        return view('dashboard.gedung.ds-admin-detail-transaction', $data);
    }
}
