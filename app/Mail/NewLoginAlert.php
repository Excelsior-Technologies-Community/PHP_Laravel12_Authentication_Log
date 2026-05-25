<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;

class NewLoginAlert extends Mailable
{
    use Queueable, SerializesModels;
    
    public $loginLog;
    
    public function __construct(AuthenticationLog $loginLog)
    {
        $this->loginLog = $loginLog;
    }
    
    public function build()
    {
        return $this->subject('New Login Detected on Your Account')
                    ->markdown('emails.new-login-alert');
    }
}