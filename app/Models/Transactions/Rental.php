<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterval;
use App\Models\User;

class Rental extends Model
{
    use HasFactory;

    protected $table = "trx_rentals";

    protected $fillable = [
        'start_date', 'end_date', 'duration',
        'cost', 'note', 'status', 'customer_id',
    ];

    protected $with = ['order'];

    public function order()
    {
        return $this->morphOne(Order::class, 'model');
    }

    public function customer() {
        return $this->belongsTo(User::class);
    }

    public function formatedDuration()
    {
        $durasi = $this->duration;
        $interval = CarbonInterval::day();

        if ($durasi > 30) {
            $interval = $interval->months(floor($durasi / 30));
            $durasi = $durasi % 30;
        }

        if ($durasi > 365) {
            $interval = $interval->years(floor($durasi / 365));
            $durasi = $durasi % 365;
        }

        $interval = $interval->addDays($durasi);

        return $interval->cascade()->locale('id')->forHumans(['join' => true]);
    }

    public function formattedStartDate()
    {
        $date = \Carbon\Carbon::parse($this->start_date)->locale('id');

        return $date->format('D d-M-Y');
    }

    public function formattedEndDate()
    {
        $date = \Carbon\Carbon::parse($this->end_date)->locale('id');

        return $date->format('D d-M-Y');
    }
}
