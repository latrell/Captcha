<?php

Route::get('/captcha', function()
{
	return app('captcha')->create();
});