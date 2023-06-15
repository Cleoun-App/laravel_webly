<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administrations\RentCanteen;

class Canteen extends Model
{
    use HasFactory;

    protected $table = "master_canteens";

    protected $fillable = [
        'name', 'image', 'slug', 'size',
        'price', 'description',
    ];

    public function rent()
    {
        return $this->hasOne(RentCanteen::class, 'canteen_id');
    }

    public function scopeGetAvailableCanteens($_) : array
    {
        $canteens = self::get()->all();

        $_canteens = [];

        foreach ($canteens as $canten) {
            if ($canten->isFree()) {
                $_canteens[] = $canten;
            }
        }

        return $_canteens;
    }

    public function isFree(): bool
    {
        if ($this->rent instanceof RentCanteen) return false;

        return true;
    }
}
