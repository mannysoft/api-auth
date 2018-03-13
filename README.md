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

## Usage
