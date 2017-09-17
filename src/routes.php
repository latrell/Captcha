<?php
Route::get(config('latrell-captcha.route_name'), [
	'middleware' => config('latrell-captcha.middleware'),
	'uses' => 'Latrell\\Captcha\\CaptchaController@getIndex'
]);
