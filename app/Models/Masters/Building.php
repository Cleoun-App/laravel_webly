<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $table = "master_buildings";

    protected $fillable = [
        'name', 'price', 'capacity', 'location',
        'image', 'category', 'description', 'slug'
    ];
}
