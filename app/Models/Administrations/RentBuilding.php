<?php

namespace App\Models\Administrations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transactions\Rental;
use App\Models\User;
use App\Models\Masters\Building;

class RentBuilding extends Model
{
    use HasFactory;

    protected $table = "rent_buildings";

    protected $with = ['rent', 'building', 'user'];

    protected $fillable = [
        'rent_id', 'building_id', 'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rent()
    {
        return $this->belongsTo(Rental::class, 'rent_id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function scopeIsBuildingRented($_, $building_id) : bool
    {
        $result = RentBuilding::where('building_id', $building_id)->first();

        if ($result == false) return false;

        return true;
    }
}
