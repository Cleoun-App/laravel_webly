<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transactions\Order;
use App\Models\Logger\RentalLog;
use App\Models\Transactions\Rental;

class RentCanteenController extends Controller
{
    //

    public function rentCanteen(Request $request)
    {
        $data['page_title'] = "Form Penyewaan Kantin";
        $data['user'] = auth()->user();

        return view('dashboard.canteen.ds-admin-rent-canteen', $data);
    }

    public function transactions()
    {
        $data['page_title'] = "Tabel Transaksi Penyewaan Kantin";
        $data['user'] = auth()->user();
        $data['orders'] = Order::where(['type' => 'rent_canteen'])->orderBy('created_at', 'DESC')->get();

        return view('dashboard.canteen.ds-admin-transactions', $data);
    }

    public function unBooking($order_key)
    {
        try {
            $order = Order::where(['key' => $order_key])->firstOrFail();

            $order->deleteTrx();

            $order->delete();

            return redirect()->route('adm.canteen.transactions')->with('success', 'Transaksi berhasil di-batalkan dan kantin di-unbooking');

            // ...
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function showTrx($tr_id)
    {
        $data['page_title'] = "Detail Penyewaan Kantin";
        $data['user'] = auth()->user();

        $order = Order::where(['key' => $tr_id])->firstOrFail();

        $data['order'] = $order;
        $data['renter'] = $order->user;
        $data['rent_data'] = $order->txData('rent_data');
        $data['payment_info'] = json_decode($order->payment_data, true);

        $data['sensor_key'] = ['merchant_id', 'order_id', 'signature_key', 'transaction_id', 'fraud_status'];

        return view('dashboard.canteen.ds-admin-detail-transaction', $data);
    }

    public function delete($order_key)
    {
        try {
            $order = Order::where(['key' => $order_key])->firstOrFail();

            $transaction = $order->transaction;

            if ($transaction instanceof Rental) {
                RentalLog::logTrx($transaction, 'cancel');
            }

            $order->deleteTrx();

            $order->delete();

            return redirect()->route('adm.canteen.transactions')
                ->with('success', 'Order dengan id "' . $order_key . '" Berhasil di hapus!!');
            // ...
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function logTransactions(Request $request)
    {

        $data['page_title'] = "Log Transaksi Penyewaan Kantin";
        $data['user'] = auth()->user();

        $data['logs'] = RentalLog::where(['type' => 'rent_canteen'])->orderBy('updated_at', 'DESC')->get();

        return view('dashboard.canteen.ds-admin-logs-transactions', $data);
    }

    public function logDetail($id)
    {
        $rentalLog = RentalLog::find($id);

        $data['page_title'] = "Log Informasi Transaksi";
        $data['user'] = auth()->user();
        $data['log'] = $rentalLog;

        return view('dashboard.canteen.ds-admin-log-detail', $data);
    }

    public function logDel($id)
    {
        try {

            $rentalLog = RentalLog::findOrFail($id);

            $rentalLog->delete();

            return redirect()->route('adm.canteen.log.transactions')->with('success', 'Data log berhasil di-hapus');
        } catch (\Throwable $th) {
            return redirect()->route('adm.canteen.log.transactions')->with('error', $th->getMessage());
        }
    }
}
