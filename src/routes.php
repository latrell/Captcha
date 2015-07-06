<?php
Route::get(Config::get('latrell-captcha.route_name'), [
	'uses' => 'Latrell\Captcha\CaptchaController@getIndex'
]);
