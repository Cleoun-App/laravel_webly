<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_price', 'model_id', 'model_type', 'number',
        'payment_status', 'payment_data',
        'payment_method', 'payment_date', 'snap_token',
    ];

    public function transaction()
    {
        return $this->morphTo('model');
    }
}
