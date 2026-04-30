<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use App\Models\AuthenticationLog; 

class User extends Authenticatable
{
    use HasFactory, Notifiable, AuthenticationLoggable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Force correct morph type
     */
    public function getMorphClass()
    {
        return 'App\\Models\\User';
    }

    /**
     * Authentication Logs Relation
     */
    public function authentications()
    {
        return $this->hasMany(
            AuthenticationLog::class,
            'authenticatable_id'
        )->where(
            'authenticatable_type',
            self::class
        )->latest('login_at');
    }
}