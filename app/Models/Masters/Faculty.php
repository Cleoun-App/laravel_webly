<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $table = "master_faculties";

    protected $fillable = [
        'name', 'image', 'slug', 'location', 'price'
    ];
}
