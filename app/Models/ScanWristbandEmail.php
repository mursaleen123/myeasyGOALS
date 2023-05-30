<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanWristbandEmail extends Model
{
    use HasFactory;

    protected $fillable = ['wristband_id', 'email'];
}
