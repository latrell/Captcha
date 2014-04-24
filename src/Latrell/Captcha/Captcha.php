<?php
namespace Latrell\Captcha;

use Gregwar\Captcha\CaptchaBuilder;
use Config, Str, Session, Hash, Response;

/**
 * 验证码
 *
 * @author Latrell Chan
 *
 */
class Captcha
{

    public static function instance()
    {
        static $object;
        if (is_null($object)) {
            $object = new static();
        }
        return $object;
    }

    /**
     * 生成验证码并输出图片
     */
    public static function create()
    {
        $builder = new CaptchaBuilder();
        $builder->build();

        Session::put('captchaHash', Hash::make(Str::lower($builder->getPhrase())));

        return Response::make($builder->get())->header('Content-type', 'image/jpeg');
    }

    /**
     * 验证码验证器
     *
     * @param string $attribute
     *            待验证属性的名字
     * @param string $value
     *            待验证属性的值
     * @param array $parameters
     *            传递给这个规则的参数
     */
    public static function check($value)
    {
        $captchaHash = (string) Session::get('captchaHash');
        Session::forget('captchaHash');
        return $captchaHash && Hash::check(Str::lower($value), $captchaHash);
    }
}