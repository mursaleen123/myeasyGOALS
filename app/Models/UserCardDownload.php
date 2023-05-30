<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCardDownload extends Model
{
    protected $fillable = [
        'userId',
        'type',
        'count'
    ];
}
