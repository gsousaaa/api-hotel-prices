<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'company_id',
    ];

    public function prices()
    {
        return $this->hasMany(Price::class)->orderByDesc('created_at');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
