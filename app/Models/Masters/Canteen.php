<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canteen extends Model
{
    use HasFactory;

    protected $table = "master_canteens";

    protected $fillable = [
        'name', 'image', 'slug', 'size',
        'price', 'description',
    ];
}
