<?php

namespace App\Services\Midtrans;

use Midtrans\Snap;

class CreateSnapTokenService extends Midtrans
{
    protected $trx_data, $item_data, $user_data;

    public function __construct($trx_data, $item_data, $user_data)
    {
        parent::__construct();

        $this->trx_data = $trx_data;
        $this->item_data = $item_data;
        $this->user_data = $user_data;
    }

    public function getSnapToken()
    {
        $params = [
            'transaction_details' => [
                'order_id' => $this->trx_data['order_id'],
                'gross_amount' => $this->trx_data['gross_amount'],
                'transaction_data' => $this->trx_data['data'] ?? [],
            ],
            'item_details' => [
                [
                    'id' => 1,
                    'price' => $this->item_data['price'],
                    'name' => $this->item_data['name'],
                    'item_data' => $this->item_data['data'] ?? [],
                    'quantity' => 1,
                ],
            ],
            'customer_details' => [
                'name' => $this->user_data['name'],
                'email' => $this->user_data['email'],
                'phone' => $this->user_data['phone'],
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return $snapToken;
    }
}
