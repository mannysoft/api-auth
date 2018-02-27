<?php

Route::group(['prefix' => 'api/v1', 'middleware' => []], function () {

	Route::post('/login', 'Mannysoft\ApiAuth\Controllers\AuthenticateController@authenticate');
});