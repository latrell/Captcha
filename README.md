# Captcha for Laravel 4

A simple [Laravel 4](http://laravel.com/) service provider for including the [Captcha for Laravel 4](https://github.com/Gregwar/Captcha).

This library is not maintained for 3rd party use.

## Preview

![Captchas examples](http://gregwar.com/captchas.png)

## Installation

The Captcha Service Provider can be installed via [Composer](http://getcomposer.org) by requiring the
`latrell/captcha` package and setting the `minimum-stability` to `dev` (required for Laravel 4) in your
project's `composer.json`.

```json
{
    "require": {
        "laravel/framework": "4.0.*",
        "latrell/captcha": "dev-master"
    },
    "minimum-stability": "dev"
}
```

Update your packages with ```composer update``` or install with ```composer install```.

## Usage

To use the Captcha Service Provider, you must register the provider when bootstrapping your Laravel application. There are
essentially two ways to do this.

Find the `providers` key in `app/config/app.php` and register the Captcha Service Provider.

```php
    'providers' => array(
        // ...
        'Latrell\Captcha\CaptchaServiceProvider',
    )
```

Find the `aliases` key in `app/config/app.php`.

```php
    'aliases' => array(
        // ...
        'Captcha' => 'Mews\Captcha\Facades\Captcha',
    )
```

## Example Usage

```php

    // [your site path]/app/routes.php

    Route::any('/captcha-test', function()
    {

        if (Request::getMethod() == 'POST')
        {
            $rules =  array('captcha' => array('required', 'captcha'));
            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails())
            {
                echo '<p style="color: #ff0000;">Incorrect!</p>';
            }
            else
            {
                echo '<p style="color: #00ff30;">Matched :)</p>';
            }
        }

        $content = Form::open(array(URL::to(Request::segment(1))));
        $content .= '<p>' . HTML::image('captcha') . '</p>';
        $content .= '<p>' . Form::text('captcha') . '</p>';
        $content .= '<p>' . Form::submit('Check') . '</p>';
        $content .= '<p>' . Form::close() . '</p>';
        return $content;

    });
```

## Links

* [L4 Captcha on Github](https://github.com/mewebstudio/captcha)
* [L4 Captcha on Packagist](https://packagist.org/packages/mews/captcha)
* [Captcha for Gregwar](https://github.com/Gregwar/Captcha)
* [License](http://www.opensource.org/licenses/mit-license.php)
* [Laravel website](http://laravel.com)
* [MeWebStudio website](http://latrell.me)
