<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportPosition extends Model
{
    use HasFactory;
    protected $fillable = [
        'sport_id',
        'position_name',
        'position_status',
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class, 'sport_id');
    }

    public function playerPosition()
    {
        return $this->hasMany(PlayerPosition::class, 'position_id');
    }
}
