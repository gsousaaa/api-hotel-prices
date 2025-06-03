<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_id',
    ];
    protected $casts = [
        'company_id' => 'integer'
    ];
    protected $table = 'users';
    protected $hidden = ['password'];

    public function getJWTCustomClaims()
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'email' => $this->email,
            'company_id' => $this->company_id
        ];
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
}
