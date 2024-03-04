<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'artist_id',
        'date_released',
        'sales',
        'genre',
        'cover',
        'description',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
