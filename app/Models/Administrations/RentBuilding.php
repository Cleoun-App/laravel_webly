<?php

namespace App\Models\Administrations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transactions\Rental;
use App\Models\Masters\Building;

class RentBuilding extends Model
{
    use HasFactory;

    protected $table = "rent_buildings";

    protected $with = ['rent', 'building'];

    protected $fillable = [
        'rent_id', 'building_id',
    ];

    public function rent()
    {
        return $this->belongsTo(Rental::class, 'rent_id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }
}
