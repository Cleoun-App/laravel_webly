<?php

namespace App\Services\Midtrans;

use App\Services\Midtrans\Midtrans;
use Midtrans\Notification;
use App\Models\Transactions\Order;

class CallbackService extends Midtrans
{
    protected $notification;
    protected $order;
    protected $serverKey;

    public function __construct()
    {
        parent::__construct();

        $this->serverKey = config('midtrans.server_key');
        $this->_handleNotification();
    }

    public function isSignatureKeyVerified()
    {
        return ($this->_createLocalSignatureKey() == $this->notification->signature_key);
    }

    public function isSuccess()
    {
        $statusCode = $this->notification->status_code;
        $transactionStatus = $this->notification->transaction_status;
        $fraudStatus = !empty($this->notification->fraud_status) ? ($this->notification->fraud_status == 'accept') : true;

        return ($statusCode == 200 && $fraudStatus && ($transactionStatus == 'capture' || $transactionStatus == 'settlement'));
    }

    public function isExpire()
    {
        return ($this->notification->transaction_status == 'expire');
    }

    public function isCancelled()
    {
        return ($this->notification->transaction_status == 'cancel');
    }

    public function isDenied()
    {
        return ($this->notification->transaction_status == 'deny');
    }

    public function isPending()
    {
        return ($this->notification->transaction_status == 'pending');
    }
    public function getNotification(): \Midtrans\Notification
    {
        return $this->notification;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    protected function _createLocalSignatureKey()
    {
        $orderId = $this->notification->order_id;
        $statusCode = $this->notification->status_code;
        $grossAmount = $this->notification->gross_amount;
        $serverKey = $this->serverKey;
        $input = $orderId . $statusCode . $grossAmount . $serverKey;
        $signature = openssl_digest($input, 'sha512');

        return $signature;
    }

    protected function _handleNotification()
    {
        $notification = new Notification();

        $order_id = $notification->order_id;
        $order = Order::where('key', $order_id)->first();

        if ($order instanceof Order === false) throw new \Exception('Data order dengan id tidak di temukan ' . $order_id);

        $this->notification = $notification;
        $this->order = $order;
    }
}
