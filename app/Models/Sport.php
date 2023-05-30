<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    use HasFactory;
    protected $fillable = [
        'sport_name',
        'sport_status',
    ];

    public function sportPosition()
    {
        return $this->hasMany(SportPosition::class, 'sport_id');
    }

    public function playerSport()
    {
        return $this->hasMany(PlayerSport::class, 'sport_id');
    }
}
