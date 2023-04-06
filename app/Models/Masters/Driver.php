<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administrations\RentCar;

class Driver extends Model
{
    use HasFactory;

    protected $table = "master_drivers";

    protected $with = ['rent_car'];

    protected $fillable = [
        'name', 'slug', 'photo', 'ktp', 'license',
        'address', 'phone_number',
    ];

    public function rent_car()
    {
        return $this->hasOne(RentCar::class, 'driver_id');
    }
}
