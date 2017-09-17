<?php
Validator::extend(config('latrell-captcha.validator_name'), function ($attribute, $value, $parameters) {
	return Captcha::check($value);
});
Validator::replacer(config('latrell-captcha.validator_name'), function ($message, $attribute, $rule, $parameters) {
	return $message ?: 'Image verification code is incorrect.';
});