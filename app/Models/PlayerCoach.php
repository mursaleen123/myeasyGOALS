<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayerCoach extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'player_id',
        'coach_id',
        'file_id',
        
    ];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function file()
    {
        return $this->belongsTo(PlayerFile::class, 'file_id');
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}
