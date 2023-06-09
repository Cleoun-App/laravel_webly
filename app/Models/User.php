<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Masters\Building;
use App\Models\Transactions\Order;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'nomor_telp',
        'image',
        'alamat_ktp',
        'alamat_sekarang',
        'kota',
        'zip',
        'sosmed',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function rent_building(Building $building)
    { }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function getPP()
    {
        return $this->image == "" || $this->image == null ? '/default.png' : $this->image;
    }
}
