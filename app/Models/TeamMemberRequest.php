<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMemberRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_uniid',
        'user_id',
        'coach_id',
        'status'
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}
