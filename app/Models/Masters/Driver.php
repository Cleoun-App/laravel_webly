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

    public function isFree()
    {
        $rent_car = $this->rent_car;

        if ($rent_car instanceof RentCar) {
            return false;
        }

        return true;
    }



    public function scopeGetAvailableDrivers($_) : array
    {
        $drivers = self::get()->all();

        $_drivers = [];

        foreach ($drivers as $dv) {
            if ($dv->isFree()) {
                $_drivers[] = $dv;
            }
        }

        return $_drivers;
    }
}
