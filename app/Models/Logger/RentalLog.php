<?php

namespace App\Models\Logger;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Transactions\Rental;

class RentalLog extends Model
{
    use HasFactory;

    protected $table = "renta_log";

    protected $with = ['rent', 'user'];

    protected $fillable = [
        'rent_id', 'user_id', 'model_id', 'model_type'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rent()
    {
        return $this->belongsTo(Rental::class, 'rent_id');
    }

    public function model(): Model
    {
        return $this->model_type::find($this->model_id);
    }
}
