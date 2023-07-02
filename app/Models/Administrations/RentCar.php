<?php

namespace App\Models\Administrations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Transactions\Rental;
use App\Models\Masters\Car;
use App\Models\Masters\Driver;

class RentCar extends Model
{
    use HasFactory;

    protected $table = "rent_cars";

    protected $fillable = [
        'user_id', 'car_id', 'driver_id', 'rent_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rent()
    {
        return $this->belongsTo(Rental::class, 'rent_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }


    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function scopeIsCarRented($_, $car_id) : bool
    {
        $result = RentCar::where('car_id', $car_id)->first();

        if ($result == false) return false;

        return true;
    }

    public function scopeIsDriverAvailable($_, $driver_id) : bool
    {
        $result = RentCar::where('driver_id', $driver_id)->first();

        if ($result == false) return true;

        return false;
    }
}
