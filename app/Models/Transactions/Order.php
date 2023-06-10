<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'transaction_data' => 'array',
        'payment_data' => 'array',
    ];

    protected $fillable = [
        'total_price', 'model_id', 'model_type', 'number', 'user_id',
        'payment_status', 'payment_data', 'transaction_data', 'type',
        'payment_method', 'payment_date', 'snap_token', 'total_payment',
    ];

    public function transaction()
    {
        return $this->morphTo('model');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deleteTrx()
    {
        $model = $this->transaction;

        if ($model instanceof Model) {
            $model->delete();
        }

        $this->update([
            'model_id' => '',
            'model_type' => '',
        ]);
    }

    public function txData($key): array
    {
        $data = $this->transaction_data ?? [];

        if (!is_array($data)) return [];

        if (empty($data)) return [];

        if (!array_key_exists($key, $data)) return [];

        return $data[$key];
    }
}
