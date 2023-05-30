<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrscanEmailVerify extends Model
{
    protected $fillable = [
        'userEmail',
        'token_2fa',
    ];
}
