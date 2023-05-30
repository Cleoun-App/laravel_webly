<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $table = "trx_rentals";

    protected $fillable = [
        'start_date', 'end_date', 'duration',
        'cost', 'note', 'status', 'customer_id',
        'payment_method', 'payment_date', 'payment_data',
        'total_payment', 'pending_expired', 'snap_token'
     ];
}
