<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = [
        'userId',
        'userEmail',
        'defaultShowCaseZone',
        'defaultGamingZone',
        'defaultPitchingColor',
        'defaultOffensiveColor',
        'defaultDefensiveColor',
        'defaultPitchingDimension',
        'defaultOffensiveDimension',
        'defaultDefensiveDimension',
        'downloadLimit',
        'token_2fa',
        'token_2fa_verify_at',
    ];
}
