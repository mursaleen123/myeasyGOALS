<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultSign extends Model
{
    protected $fillable = [
        'name',
        'abbreviation',
        'color',
        'category',
    ];
}
