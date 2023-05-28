<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administrations\RentBuilding;

class Building extends Model
{
    use HasFactory;

    protected $table = "master_buildings";

    protected $fillable = [
        'name', 'price', 'capacity', 'location',
        'image', 'category', 'description', 'slug'
    ];


    public function rent()
    {
        return $this->hasOne(RentBuilding::class, 'building_id');
    }

    public function scopeGetAvailableBuildings($_)
    {
        $buildings = self::get()->all();

        $_buildings = [];

        foreach ($buildings as $building) {
            if ($building->isFree()) {
                $_buildings[] = $building;
            }
        }

        return $_buildings;
    }

    public function isFree(): bool
    {
        if ($this->rent instanceof RentBuilding) return false;

        return true;
    }
}
