<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayerFile extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'file_uniid',
        'file_name',
        'file_link',
        'coach_id',
        'parent_id'
    ];

    public function player()
    {
        return $this->hasMany(PlayerCoach::class, 'file_id');
    }

    public function attachment()
    {
        return $this->hasMany(PlayerFileAttachment::class, 'player_file_id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}
