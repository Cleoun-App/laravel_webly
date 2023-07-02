<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administrations\RentCar;

class Car extends Model
{
    use HasFactory;

    protected $table = "master_cars";

    protected $with = ['rent'];

    protected $fillable = [
        'name', 'type', 'km', 'license_plate',
        'stnk', 'description', 'price', 'slug', 'image',
    ];

    public function rent()
    {
        return $this->hasOne(RentCar::class, 'car_id');
    }

    public function scopeGetAvailableCars($_) : array
    {
        $cars = self::get()->all();

        $_cars = [];

        foreach ($cars as $car) {
            if ($car->isFree()) {
                $_cars[] = $car;
            }
        }

        return $_cars;
    }

    public function isFree(): bool
    {
        if ($this->rent instanceof RentCar) return false;

        return true;
    }
}
