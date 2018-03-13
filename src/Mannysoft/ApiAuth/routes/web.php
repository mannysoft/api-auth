<?php

Route::post(config('api-auth.login_url'), 'Mannysoft\ApiAuth\Controllers\AuthenticateController@authenticate');
Route::post(config('api-auth.logout_url'), 'Mannysoft\ApiAuth\Controllers\AuthenticateController@logout')->middleware('auth:api');
Route::post(config('api-auth.login_facebook'), 'Mannysoft\ApiAuth\Controllers\AuthenticateController@loginFacebook');
Route::post(config('api-auth.login_accountkit'), 'Mannysoft\ApiAuth\Controllers\AuthenticateController@accountKit');
// forgot password
// sign up
// sign up using fb account kit