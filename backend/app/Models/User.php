<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'identification_number',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'location',
        'password',
        'type',
        'first_time_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'first_time_login' => 'boolean',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function professionalHistorias()
    {
        return $this->hasMany(Historia::class, 'professional_id');
    }

    public function patientHistorias()
    {
        return $this->hasMany(Historia::class, 'patient_id');
    }
}
