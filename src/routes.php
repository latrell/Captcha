<?php

Route::get(Config::get('latrell-captcha.route_name'), function()
{
	return app('captcha')->create();
});