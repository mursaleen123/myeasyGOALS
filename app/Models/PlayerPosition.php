<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerPosition extends Model
{
    use HasFactory;

    protected $fillable = ['player_id', 'position_id', 'position_name'];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function position()
    {
        return $this->belongsTo(SportPosition::class, 'position_id');
    }
}
