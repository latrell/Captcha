<?php

Route::get(Config::get('captcha::route_name'), function()
{
	return app('captcha')->create();
});