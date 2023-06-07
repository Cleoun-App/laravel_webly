<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'payment_data' => 'array',
        'transaction_data' => 'array',
    ];

    protected $fillable = [
        'total_price', 'model_id', 'model_type', 'number',
        'payment_status', 'payment_data', 'transaction_data', 'type',
        'payment_method', 'payment_date', 'snap_token',
    ];

    public function transaction()
    {
        return $this->morphTo('model');
    }

    public function deleteTrx() {
        $model = $this->transaction;

        if($model instanceOf Model) {
            $model->delete();
        }
    }
}
