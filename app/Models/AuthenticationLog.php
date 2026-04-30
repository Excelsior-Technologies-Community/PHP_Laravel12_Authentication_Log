<?php

namespace App\Models;

use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog as BaseAuthenticationLog;

class AuthenticationLog extends BaseAuthenticationLog
{
    protected static function booted()
    {
        static::creating(function ($log) {
            // FORCE correct value
            $log->authenticatable_type = \App\Models\User::class;
        });
    }
}