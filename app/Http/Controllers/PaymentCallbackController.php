<?php

namespace App\Http\Controllers;

use App\Services\Midtrans\CallbackService;
use App\Models\Transactions\Order;
use Carbon\Carbon;
use App\Models\Logger\RentalLog;
use App\Models\Transactions\Rental;

class PaymentCallbackController extends Controller
{

    public function receive()
    {
        try {

            $callback = new CallbackService();

            if ($callback->isSignatureKeyVerified()) {

                $notification = $callback->getNotification();

                $order = $callback->getOrder();

                $new_status = 'error';

                if ($callback->isSuccess()) {
                    $new_status = 'success';
                }

                if ($callback->isExpire()) {
                    $new_status = 'expired';
                    $order->deleteTrx();
                }

                if ($callback->isCancelled()) {
                    $new_status = 'cancel';
                    $order->deleteTrx();
                }

                if ($callback->isDenied()) {
                    $new_status = 'cancel';
                    $order->deleteTrx();
                }

                if ($callback->isPending()) {
                    $new_status = 'pending';
                }

                $_notif = $notification->getResponse();

                $order->update([
                    'total_payment' => $notification->gross_amount,
                    'payment_status' => $new_status,
                    'payment_data' => json_encode($_notif ?? ''),
                    'payment_method' => $notification->payment_type ?? '',
                    'payment_date' => Carbon::now(),
                ]);

                $model = $order->transaction;

                if ($model instanceof Rental) {
                    RentalLog::logTrx($model, $new_status);
                }

                return response()
                    ->json([
                        'success' => true,
                        'message' => 'Notifikasi berhasil diproses',
                    ]);
            } else {
                return response()
                    ->json([
                        'error' => true,
                        'message' => 'Signature key tidak terverifikasi',
                    ], 403);
            }
        } catch (\Throwable $th) {
            throw $th;
            return response()
                ->json([
                    'error' => true,
                    'message' => $th->getMessage(),
                ], 500);
        }
    }
}
