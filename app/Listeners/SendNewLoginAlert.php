<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewLoginAlert;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;

class SendNewLoginAlert
{
    public function handle(Login $event)
    {
        // Get the latest login log for this user
        $latestLogin = AuthenticationLog::where('authenticatable_id', $event->user->id)
            ->latest('login_at')
            ->first();
            
        // Send email alert
        if ($latestLogin && $event->user->email) {
            Mail::to($event->user->email)->send(new NewLoginAlert($latestLogin));
        }
    }
}