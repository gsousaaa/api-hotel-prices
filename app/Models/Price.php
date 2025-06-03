<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'price',
        'effective_date',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
