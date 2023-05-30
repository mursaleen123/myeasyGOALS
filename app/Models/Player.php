<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'file_id',
        'coach_id',
        'player_uniid',
        'is_completed',
        'player_name',
        'player_phone',
        'player_email',
        'player_agl_number',
        'player_gpa',
        'player_grad_year',
        'player_position',
        'player_twitter',
        'player_sat_number',
        'player_school',
        'player_role',
        'player_club',
        'player_insta'
    ];

    public function playerFile()
    {
        return $this->belongsTo(PlayerFile::class, 'file_id');
    }

    public function playerSport()
    {
        return $this->hasMany(PlayerSport::class, 'player_id');
    }

    public function playerPosition()
    {
        return $this->hasMany(PlayerPosition::class, 'player_id');
    }

    // this is a recommended way to declare event handlers
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($player) { 
            $player->playerSport()->delete();
            $player->playerPosition()->delete();
            
        });
    }
}
