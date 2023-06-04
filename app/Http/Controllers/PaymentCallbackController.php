<?php

namespace App\Http\Controllers;

use App\Services\Midtrans\CallbackService;
use App\Models\Transactions\Order;
use Carbon\Carbon;

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
                }

                if ($callback->isCancelled()) {
                    $new_status = 'cancel';
                }

                $_notif = $notification->getResponse();

                $order->update([
                    'payment_status' => $new_status,
                    'payment_data' => json_encode($_notif ?? ''),
                    'payment_method' => $notification->payment_type ?? '',
                    'payment_date' => Carbon::now(),
                ]);

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
            return response()
                ->json([
                    'error' => true,
                    'message' => $th->getMessage(),
                ], 500);
        }
    }
}
