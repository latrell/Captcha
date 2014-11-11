# Captcha for Laravel 4

A simple [Laravel 4](http://laravel.com/) service provider for including the [Captcha for Laravel 4](https://github.com/Gregwar/Captcha).

This library is not maintained for 3rd party use.

## Preview

![Captchas examples](http://gregwar.com/captchas.png)

## Installation

```
composer require latrell/captcha dev-master
```

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
        'Captcha' => 'Latrell\Captcha\Facades\Captcha',
    )
```

Then publish the config file with `php artisan config:publish latrell/captcha`. This will add the file `app/config/packages/latrell/captcha/config.php`.
This config file is the primary way you interact with Captcha.

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
        $content .= '<p>' . HTML::image(Captcha::url()) . '</p>';
        $content .= '<p>' . Form::text('captcha') . '</p>';
        $content .= '<p>' . Form::submit('Check') . '</p>';
        $content .= '<p>' . Form::close() . '</p>';
        return $content;

    });
```

## Links

* [L4 Captcha on Github](https://github.com/latrell/captcha)
* [L4 Captcha on Packagist](https://packagist.org/packages/Latrell/captcha)
* [Captcha for Gregwar](https://github.com/Gregwar/Captcha)
* [License](http://www.opensource.org/licenses/mit-license.php)
* [Laravel website](http://laravel.com)
* [MeWebStudio website](http://latrell.me)
