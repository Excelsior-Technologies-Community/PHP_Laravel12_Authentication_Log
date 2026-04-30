<?php

return [

    // The database table name
    'table_name' => 'authentication_log',

       // ADD THIS
    'model' => \App\Models\AuthenticationLog::class,

    // Database connection (null = default)
    'db_connection' => null,

    // Events to listen
    'events' => [
        'login' => \Illuminate\Auth\Events\Login::class,
        // 'failed' => \Illuminate\Auth\Events\Failed::class,
        'logout' => \Illuminate\Auth\Events\Logout::class,
        'other-device-logout' => \Illuminate\Auth\Events\OtherDeviceLogout::class,
    ],

    'listeners' => [
        'login' => \Rappasoft\LaravelAuthenticationLog\Listeners\LoginListener::class,
        // 'failed' => \Rappasoft\LaravelAuthenticationLog\Listeners\FailedLoginListener::class,
        'logout' => \Rappasoft\LaravelAuthenticationLog\Listeners\LogoutListener::class,
        'other-device-logout' => \Rappasoft\LaravelAuthenticationLog\Listeners\OtherDeviceLogoutListener::class,
    ],

    'notifications' => [
        'new-device' => [
            'enabled' => env('NEW_DEVICE_NOTIFICATION', true),
            'location' => false, // ❌ disable GeoIP
            'template' => \Rappasoft\LaravelAuthenticationLog\Notifications\NewDevice::class,
            'rate_limit' => env('NEW_DEVICE_NOTIFICATION_RATE_LIMIT', 3),
            'rate_limit_decay' => env('NEW_DEVICE_NOTIFICATION_RATE_LIMIT_DECAY', 60),
            'new_user_threshold_minutes' => env('NEW_DEVICE_NEW_USER_THRESHOLD_MINUTES', 1),
        ],
        'failed-login' => [
            'enabled' => env('FAILED_LOGIN_NOTIFICATION', false),
            'location' => false, // ❌ disable GeoIP
            'template' => \Rappasoft\LaravelAuthenticationLog\Notifications\FailedLogin::class,
            'rate_limit' => env('FAILED_LOGIN_NOTIFICATION_RATE_LIMIT', 5),
            'rate_limit_decay' => env('FAILED_LOGIN_NOTIFICATION_RATE_LIMIT_DECAY', 60),
        ],
        'suspicious-activity' => [
            'enabled' => env('SUSPICIOUS_ACTIVITY_NOTIFICATION', false),
            'location' => false, // ❌ disable GeoIP
            'template' => \Rappasoft\LaravelAuthenticationLog\Notifications\SuspiciousActivity::class,
            'rate_limit' => env('SUSPICIOUS_ACTIVITY_NOTIFICATION_RATE_LIMIT', 3),
            'rate_limit_decay' => env('SUSPICIOUS_ACTIVITY_NOTIFICATION_RATE_LIMIT_DECAY', 60),
        ],
    ],

    'suspicious' => [
        'failed_login_threshold' => env('AUTH_LOG_SUSPICIOUS_FAILED_THRESHOLD', 5),
        'check_unusual_times' => env('AUTH_LOG_CHECK_UNUSUAL_TIMES', false),
        'usual_hours' => [9,10,11,12,13,14,15,16,17],
    ],

    'webhooks' => [],
    'webhook_settings' => [
        'log_failures' => env('AUTH_LOG_WEBHOOK_LOG_FAILURES', true),
        'timeout' => env('AUTH_LOG_WEBHOOK_TIMEOUT', 10),
    ],

    'purge' => 365,
    'prevent_session_restoration_logging' => env('AUTH_LOG_PREVENT_SESSION_RESTORATION', true),
    'session_restoration_window_minutes' => env('AUTH_LOG_SESSION_RESTORATION_WINDOW', 5),
    'behind_cdn' => false,
];