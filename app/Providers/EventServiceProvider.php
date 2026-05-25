<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use App\Listeners\SendNewLoginAlert;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            SendNewLoginAlert::class,
        ],
    ];
    
    public function boot(): void
    {
        //
    }
}