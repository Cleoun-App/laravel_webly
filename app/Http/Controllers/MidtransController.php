<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Midtrans\Midtrans;
use App\Models\Transactions\Order;

class MidtransController extends Controller
{
    //

    public function cancel(Request $request)
    {
        try {

            $midtrans = new Midtrans;

            $response = $midtrans->cancel($request->id);

            $order = Order::where(['key' => $request->id])->firstOrFail();

            $transaction = $order->transaction;

            if ($transaction instanceof Rental) {
                RentalLog::logTrx($transaction, 'cancel');
            }

            $order->deleteTrx();

            return redirect()->route('adm.building.transactions')->with('success', $response);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function refund(Request $request)
    {
        try {

            $midtrans = new Midtrans;

            $order = Order::where(['key' => $request->id])->firstOrFail();

            $response = $midtrans->refund($order->key, $order->total_price);

            $transaction = $order->transaction;

            if ($transaction instanceof Rental) {
                RentalLog::logTrx($transaction, 'refund');
            }

            $order->deleteTrx();

            return redirect()->route('adm.building.transactions')->with('success', $response);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
