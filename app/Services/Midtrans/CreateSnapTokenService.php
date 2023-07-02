<?php

namespace App\Services\Midtrans;

use Midtrans\Snap;

class CreateSnapTokenService extends Midtrans
{
    protected $trx_data, $item_data, $user_data, $driver_data;

    public function __construct($trx_data, $item_data, $user_data)
    {
        parent::__construct();

        $this->trx_data = $trx_data;
        $this->item_data = $item_data;
        $this->user_data = $user_data;
        $this->driver_data = $item_data['driver_data'];
    }

    public function getSnapToken()
    {
        $item_details = [
            [
                'id' => '001',
                'price' => intval($this->trx_data['gross_amount']),
                'name' => $this->item_data['name'],
                'item_data' => $this->item_data['data'] ?? [],
                'quantity' => 1,
            ],
            [
                'id' => '002',
                'price' => intval($this->trx_data['tax_fee']),
                'name' => 'Pajak',
                'quantity' => 1,
            ],
            [
                'id' => '003',
                'price' => intval($this->trx_data['adm_fee']),
                'name' => 'Biaya Administrasi',
                'quantity' => 1,
            ],
        ];

        if(is_array($this->driver_data)) {
            $item_details[] = [
                'id' => '004',
                'price' => intval($this->driver_data['cost']),
                'name' => 'Biaya Driver',
                'quantity' => 1,
            ];
        }

        $params = [
            'transaction_details' => [
                'isaku' => 'abdilah',
                'order_id' => $this->trx_data['order_id'],
                'gross_amount' => $this->trx_data['gross_amount'],
                'transaction_data' => $this->trx_data['data'] ?? [],
            ],
            'item_details' => $item_details,
            'customer_details' => [
                'isaku' => 'abdilah',
                'name' => $this->user_data['name'],
                'email' => $this->user_data['email'],
                'phone' => $this->user_data['phone'],
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return $snapToken;
    }
}
