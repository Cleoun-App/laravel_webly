<?php

namespace App\Models\Logger;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Transactions\Rental;

class RentalLog extends Model
{
    use HasFactory;

    protected $table = "rental_logs";

    protected $casts = [
        'transaction_data' => 'array'
    ];

    protected $with = ['user'];

    protected $fillable = [
        'user_id', 'type', 'name', 'start_date', 'end_date', 'duration', 'rental_cost', 'total_payment',
        'payment_method', 'transaction_data', 'log_id', 'payment_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeLogTrx($_, Rental $rental, string $trx_status = null)
    {

        $order = $rental->order;

        if (!in_array($order->type, $this->getRentType())) throw new \Exception('Order type tidak tersedia ' . $order->type);

        $is_new = false;
        $log_id = md5($rental->id . $order->key);

        $logger = self::where(['log_id' => $log_id])->first();

        if ($logger instanceof self === false) {
            $logger = new self;
            $is_new = true;
        }

        $logger->user()->associate($rental->customer);

        $logger->log_id = $log_id;
        $logger->name = strtoupper(uniqid("L" . rand(10, 99) . 'MX'));
        $logger->type = $order->type;
        $logger->start_date = $rental->start_date;
        $logger->end_date = $rental->end_date;
        $logger->duration = $rental->duration;
        $logger->rental_cost = $rental->cost;
        $logger->total_payment = $order->total_payment;
        $logger->payment_method = $order->payment_method;
        $logger->transaction_data = $order->transaction_data;
        $logger->payment_status = $trx_status ?? $order->payment_status;

        $logger->save();
    }

    private function getRentType(): array
    {
        return ['rent_building', 'rent_car', 'rent_canteen'];
    }

    private function getStatusTypes(): array
    {
        return ['waiting', 'success', 'expired', 'cancel', 'error', 'pending', 'refund'];
    }
}
