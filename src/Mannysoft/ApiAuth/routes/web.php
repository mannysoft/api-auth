<?php
Route::post(config('api-auth.register'), 'Mannysoft\ApiAuth\Controllers\AuthenticateController@register');
Route::post(config('api-auth.login_url'), 'Mannysoft\ApiAuth\Controllers\AuthenticateController@authenticate');
Route::post(config('api-auth.logout_url'), 'Mannysoft\ApiAuth\Controllers\AuthenticateController@logout')->middleware('auth:api');
Route::get(config('api-auth.profile'), 'Mannysoft\ApiAuth\Controllers\AuthenticateController@me');
Route::post(config('api-auth.login_facebook'), 'Mannysoft\ApiAuth\Controllers\AuthenticateController@loginFacebook');
Route::post(config('api-auth.login_accountkit'), 'Mannysoft\ApiAuth\Controllers\AuthenticateController@accountKit');
Route::post(config('api-auth.forgot_password'), 'Mannysoft\ApiAuth\Controllers\AuthenticateController@sendResetPasswordEmail');
Route::post(config('api-auth.change_password'), 'Mannysoft\ApiAuth\Controllers\ResetPasswordController@reset');
