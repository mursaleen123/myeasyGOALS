<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerFileAttachment extends Model
{
    use HasFactory;
    protected $fillable = ['player_file_id', 'file_attachment'];

    public function playerFile()
    {
        return $this->belongsTo(PlayerFile::class, 'player_file_id');
    }
}
