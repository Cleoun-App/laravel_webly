<?php

namespace App\Models\Administrations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Masters\Canteen;
use App\Models\Transactions\Rental;
use App\Models\User;

class RentCanteen extends Model
{
    use HasFactory;

    protected $table = "rent_canteens";

    protected $fillable = [
        'rent_id', 'user_id', 'canteen_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rent()
    {
        return $this->belongsTo(Rental::class, 'rent_id');
    }

    public function canteen()
    {
        return $this->belongsTo(Canteen::class, 'canteen_id');
    }

    public function scopeIsRented($_, $canteen_id) : bool
    {
        $result = RentCanteen::where('canteen_id', $canteen_id)->first();

        if ($result == false) return false;

        return true;
    }
}
