# API Auth Request
Laravel package for extending Laravel Passport.

## Installation

Require this package with composer.

```shell
composer require mannysoft/api-auth
```
```shell
php artisan migrate
```
```shell
php artisan passport:install
```
```shell
php artisan passport:keys
```
```shell
php artisan vendor:publish --provider="Mannysoft\ApiAuth\ServiceProvider"
```

Open your oauth_clients table and look for password_client

Change your .env

APP_OAUTH_CLIENT_ID=

APP_OAUTH_CLIENT_SECRET=

Laravel 5.5 uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.


add the Laravel\Passport\HasApiTokens trait to your App\User model. This trait will provide a few helper methods to your model which allow you to inspect the authenticated user's token and scopes:


```php
<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
}
```


Update you config/services.php


```php
'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT'),
],
```


Finally, in your config/auth.php configuration file, you should set the driver option of the  api authentication guard to passport. This will instruct your application to use Passport's  TokenGuard when authenticating incoming API requests:


```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],
```
## Usage
