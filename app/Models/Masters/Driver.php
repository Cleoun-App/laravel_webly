<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = "master_drivers";

    protected $fillable = [
        'name', 'slug', 'photo', 'ktp', 'license',
        'address', 'phone_number',
    ];


}
