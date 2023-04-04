<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $table = "master_cars";

    protected $fillable = [
        'name', 'type', 'km', 'license_plate',
        'stnk', 'description', 'price', 'slug', 'image',
    ];
}
