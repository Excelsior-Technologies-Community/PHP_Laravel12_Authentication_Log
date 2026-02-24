# PHP_Laravel12_Authentication_Log 

## Project Description

PHP_Laravel12_Authentication_Log is a Laravel 12 based web application that records and displays user authentication activity such as login time, IP address, and browser/device information.

This project uses the Laravel framework along with the Rappasoft Laravel Authentication Log package to automatically track authentication events without manually writing logging logic.

When a user logs in, the system automatically stores authentication details in the database. These records can then be viewed in a user-friendly dashboard table.

## This project is useful for:

- Learning Laravel authentication logging

- Understanding login event tracking

- Monitoring user login activity

- Improving application security

- Learning package integration in Laravel 12


## Authentication Features

- User Registration

- User Login

- User Logout

- Secure Authentication System

- Session Management


## Authentication Log Features

- Automatically records login activity

- Stores user login time

- Stores IP address

- Stores browser and device information

- Stores logout time

- Displays login history in table format

- Shows only logged-in user's activity


## Technologies Used

- PHP 8.2+
- Laravel 12 Framework
- MySQL Database
- Bootstrap 5
- Laravel Breeze
- Rappasoft Laravel Authentication Log Package
- Composer
- NPM


---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Authentication_Log "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Authentication_Log 

```

#### Explanation:

This installs a fresh Laravel 12 project with all core files and moves into the project folder.





## STEP 2: Database Setup 

### Open .env and set:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=authentication_log_laravel12
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: authentication_log_laravel12

```

#### Explanation:

This connects Laravel to your MySQL database so login and authentication log data can be stored.




## STEP 3: Install the Authentication Log Package

### Use composer to install the package:

```
composer require rappasoft/laravel-authentication-log

```


#### Explanation:

This installs the package that automatically records login, logout, IP address, and device info.

This installs the latest version compatible with Laravel 11/12





## STEP 4: Publish Migrations + Config + Views

### Publish everything the package offers:

#### Publish Migrations (Windows)

```
php artisan vendor:publish --provider="Rappasoft\LaravelAuthenticationLog\LaravelAuthenticationLogServiceProvider" --tag="authentication-log-migrations"

```


#### Publish Config

```
php artisan vendor:publish --provider="Rappasoft\LaravelAuthenticationLog\LaravelAuthenticationLogServiceProvider" --tag="authentication-log-config"

```


#### Publish Views

```
php artisan vendor:publish --provider="Rappasoft\LaravelAuthenticationLog\LaravelAuthenticationLogServiceProvider" --tag="authentication-log-views"

```


### Run Migrations:

```
php artisan migrate

```


#### Explanation:

This creates the authentication_log table and publishes config files needed by the package.




## STEP 5: Add Trait to Your User Model

### Open app/Models/User.php and add this trait (package listens for login/logout events):

```
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;

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

    // Authentication Logs Relation
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

```

#### Explanation:

This enables automatic tracking of login/logout activity for each user.



## STEP 6: (Optional) GeoIP for Location Info

### If you want location data for log entries:

```
composer require torann/geoip

```

### Publish its config:

```
php artisan vendor:publish --provider="Torann\GeoIP\GeoIPServiceProvider" --tag=config

```

#### Explanation:

This adds location tracking (country, city) based on IP address.





## STEP 7: Setup Laravel Authentication (Login/Register)

### Install Breeze for simple auth scaffolding:

```
composer require laravel/breeze --dev

php artisan breeze:install

npm install

npm run dev

php artisan migrate

```

#### Explanation:

This installs login, register, logout, and dashboard functionality.





## STEP 8: View Authentication Log in UI

### Create a Blade view resources/views/auth-log.blade.php:

```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication Log</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Authentication Log</h1>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>User Type</th>
                <th>IP Address</th>
                <th>Browser/Device</th>
                <th>Login At</th>
            </tr>
        </thead>
        <tbody>
            @forelse(auth()->user()->authentications as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->authenticatable_id }}</td>
                    <td>{{ $log->authenticatable_type }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->user_agent }}</td>
                    <td>{{ $log->login_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No login records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

```

#### Explanation:

This page displays the user's login history in a table format.





## STEP 9: Open this file:

### routes/web.php


#### Then add this code at the bottom:

```
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/auth-log', function () {
        return view('auth-log');
    })->name('auth-log');
});

```

#### Explanation:

This creates a secure route to view authentication logs only for logged-in users.





## STEP 10: Change login redirect

### In AuthenticatedSessionController.php, after login, redirect to /auth-log:

```
public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect to auth-log page immediately after login
        return redirect()->route('auth-log');
    }

```


#### Explanation:

This redirects the user to the authentication log page after login.




## STEP 11: Update the table (quick fix)

### Run a migration to ensure authenticatable_type has default:

```
php artisan make:migration fix_authenticatable_type_default --table=authentication_log

```

### In the migration:

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('authentication_log', function (Blueprint $table) {
            // Make sure the column exists
            $table->string('authenticatable_type')->default('App\\Models\\User')->change();
        });
    }

    public function down(): void
    {
        Schema::table('authentication_log', function (Blueprint $table) {
            $table->string('authenticatable_type')->change();
        });
    }
};

```


### Then run:

```
php artisan migrate

```

## STEP 12: Run Server

### Open new terminal in this project:

```
npm run dev

```

### Run: 

```
php artisan serve

```

### Open:

```
http://127.0.0.1:8000/auth-log

```

#### Explanation:

This starts Laravel server so you can access login and auth log pages.




## STEP 13: VERY IMPORTANT — Check Database Value

### Go to phpMyAdmin and check:

```
authentication_log table

```

### Look at column:

```
authenticatable_type

```

### It MUST be exactly:

```
App\Models\User

```

### If you see this:

```
AppModelsUser

OR

App\Models\User\

```

#### OR anything different


### Then run this SQL in phpMyAdmin:

```
UPDATE authentication_log
SET authenticatable_type = 'App\\Models\\User';

```

After this → Refresh page.



## So you can see this type output:

### Register Page:


<img width="1919" height="955" alt="Screenshot 2026-02-24 105238" src="https://github.com/user-attachments/assets/87bc4ad5-b3a4-4a7b-b0d2-6d2f670e9e63" />


### Login Page:


<img width="1919" height="962" alt="Screenshot 2026-02-24 105305" src="https://github.com/user-attachments/assets/8ec09a63-86e3-42f4-a1cb-0d17781188e6" />


### Auth-Log Page:


<img width="1919" height="954" alt="Screenshot 2026-02-24 105428" src="https://github.com/user-attachments/assets/dc00be32-fbb1-4271-8eff-5f533f889a59" />



---

# Project Folder Structure:

```
PHP_Laravel12_Authentication_Log/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   ├── RegisteredUserController.php
│   │   │   │   ├── PasswordResetLinkController.php
│   │   │   │   ├── NewPasswordController.php
│   │   │   │   └── EmailVerificationController.php
│   │   │   │
│   │   │   └── Controller.php
│   │   │
│   │   └── Requests/
│   │       └── Auth/
│   │           └── LoginRequest.php
│   │
│   ├── Models/
│   │   └── User.php
│   │
│   └── Providers/
│       └── AppServiceProvider.php
│
├── bootstrap/
│
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── authentication-log.php    (published config)
│   └── geoip.php (optional)
│
├── database/
│   ├── factories/
│   │   └── UserFactory.php
│   │
│   ├── migrations/
│   │   ├── 0001_create_users_table.php
│   │   ├── 0001_create_cache_table.php
│   │   ├── 0001_create_jobs_table.php
│   │   └── xxxx_xx_xx_create_authentication_log_table.php  
│   │
│   └── seeders/
│       └── DatabaseSeeder.php
│
├── public/
│   └── index.php
│
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   └── register.blade.php
│   │   │
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   │
│   │   ├── dashboard.blade.php
│   │   ├── welcome.blade.php
│   │   │
│   │   └── auth-log.blade.php     (your authentication log page)
│   │
│   ├── css/
│   └── js/
│
├── routes/
│   ├── web.php     
│   └── auth.php
│
├── storage/
│
├── tests/
│
├── vendor/
│
├── .env
├── artisan
├── composer.json
├── package.json
└── README.md
```
