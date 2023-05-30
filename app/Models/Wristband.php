<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wristband extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color', 'wristband_json', 'user_id', 'mode'];

    public function scanEmail()
    {
        return $this->hasMany(ScanWristbandEmail::class);
    }
}
