<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'trx_purchases';

    protected $fillable = [
        'name', 'slug', 'purchase_date', 'total_cost',
        'payment_method', 'payment_date', 'discount',
        'note', 'model_id', 'model_type', 'status'
    ];
}
