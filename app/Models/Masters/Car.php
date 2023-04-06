<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administrations\RentCar;

class Car extends Model
{
    use HasFactory;

    protected $table = "master_cars";

    protected $with = ['rent_car'];

    protected $fillable = [
        'name', 'type', 'km', 'license_plate',
        'stnk', 'description', 'price', 'slug', 'image',
    ];

    public function rent_car()
    {
        return $this->hasOne(RentCar::class, 'car_id');
    }
}
