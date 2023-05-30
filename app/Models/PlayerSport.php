<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerSport extends Model
{
    use HasFactory;

    protected $fillable = ['player_id', 'sport_id', 'sport_name'];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class, 'sport_id');
    }
}
