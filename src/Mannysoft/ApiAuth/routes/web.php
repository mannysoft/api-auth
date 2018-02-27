<?php
Route::post(config('api-auth.login_url'), 'Mannysoft\ApiAuth\Controllers\AuthenticateController@authenticate');