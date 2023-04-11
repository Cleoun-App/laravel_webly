<?php

namespace App\Models\Administrations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentCanteen extends Model
{
    use HasFactory;

    protected $table = "rent_canteens";

    protected $fillable = [
        'rent_id', 'user_id', 'canteen_id'
    ];

    public function scopeIsRented($_, $canteen_id) : bool
    {
        $result = RentCanteen::where('canteen_id', $canteen_id)->first();

        if ($result == false) return false;

        return true;
    }
}
