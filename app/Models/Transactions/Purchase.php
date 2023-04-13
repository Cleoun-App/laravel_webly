<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'trx_purchases';

    protected $fillable = [
        'name', 'slug', 'total_cost', 'payment_data',
        'payment_method', 'payment_date', 'discount',
        'note', 'exp_date', 'status'
    ];
}
