<?php

return [
	'login_facebook' => 'api/v1/login/facebook',
	'login_accountkit' => 'api/v1/login/facebook/accountkit',
	'login_url' => 'api/v1/login',
	'logout_url' => 'api/v1/logout',
	'username' => 'email', // email or username

	'app_oauth' => [
        'grant_type' => env('APP_OAUTH_GRANT_TYPE', 'password'),
        'client_id' => env('APP_OAUTH_CLIENT_ID', 1),
        'client_secret' => env('APP_OAUTH_CLIENT_SECRET', 'HEKKXk9gAX0GgLQBBKYPgh9kEjDrNCJNwBtgeA9W'),
        'scope' => env('SCOPE', 'show'),
    ],
	'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID', ''),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET', ''),
        'redirect' => env('FACEBOOK_REDIRECT', ''),
    ],
    'accountkit' => [
        'client_id' => env('ACCOUNT_KIT_APP_ID', ''),
        'client_secret' => env('ACCOUNT_KIT_APP_SECRET', ''),
    ],
];