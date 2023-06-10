<?php

namespace App\Services\Midtrans;

use Midtrans\Config;
use Midtrans\Transaction;

class Midtrans
{
    protected $serverKey;
    protected $isProduction;
    protected $isSanitized;
    protected $is3ds;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->isProduction = config('midtrans.is_production');
        $this->isSanitized = config('midtrans.is_sanitized');
        $this->is3ds = config('midtrans.is_3ds');

        $this->_configureMidtrans();
    }

    public function _configureMidtrans()
    {
        Config::$serverKey = $this->serverKey;
        Config::$isProduction = $this->isProduction;
        Config::$isSanitized = $this->isSanitized;
        Config::$is3ds = $this->is3ds;
    }

    public function cancel($orderId)
    {
        $resp = Transaction::cancel($orderId);

        if ($resp === '200') {
            // Transaksi berhasil
            return 'Transaksi berhasil di cancel';
        } else {
            // Gagal membatalkan transaksi
            return 'Transaksi Tidak Dapat Di-cancel';
        }
    }

    public function refund($orderId, $refundAmount)
    {
        return Transaction::refund($orderId, $refundAmount);
    }

    /**
     *  Expire kan transaksi sebelum berhasil
     */
    public function expire($orderId)
    {
        $resp = Transaction::expire($orderId);

        if ($resp->status_code === '200') {
            // Transaksi berhasil
            return 'Transaksi Expired';
        } else {
            // Gagal membatalkan transaksi
            return 'Transaksi Tidak Expired';
        }
    }
}
