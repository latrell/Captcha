<?php
namespace Latrell\Captcha;

use Gregwar\Captcha\CaptchaBuilder;
use Config, Str, Session, Hash, Response, URL;

/**
 * 验证码
 *
 * @author Latrell Chan
 *
 */
class Captcha
{
    // Enable or disable the distortion.
    protected $distortion = true;

    // Builds a code until it is not readable by ocrad.
    // You'll need to have shell_exec enabled, imagemagick and ocrad installed.
    protected $against_ocr = false;

    // Builds a code with the given width, height and font. By default, a random font will be used from the library.
    protected $width = 150;

    protected $height = 40;

    protected $font = null;

    // Setting the picture quality.
    protected $quality = 80;

    // Sets the background color to force it (this will disable many effects and is not recommended).
    protected $background_color = null; // [0x00, 0x00, 0x00] or #000000


    // Sets custom background images to be used as captcha background.
    // It is recommended to disable image effects when passing custom images for background (ignore_all_effects).
    // A random image is selected from the list passed, the full paths to the image files must be passed.
    protected $background_images = [];

    // Enable or disable the interpolation (enabled by default), disabling it will be quicker but the images will look uglier.
    protected $interpolate = true;

    // Disable all effects on the captcha image. Recommended to use when passing custom background images for the captcha.
    protected $ignore_all_effects = false;

    public function __construct()
    {
        $configKey = 'captcha::';

        $this->distortion = Config::get($configKey . 'distortion');
        $this->against_ocr = Config::get($configKey . 'against_ocr');
        $this->width = Config::get($configKey . 'width');
        $this->height = Config::get($configKey . 'height');
        $this->font = Config::get($configKey . 'font');
        $this->quality = Config::get($configKey . 'quality');
        $this->background_color = Config::get($configKey . 'background_color'); // [0x00, 0x00, 0x00] or #000000
        $this->background_images = Config::get($configKey . 'background_images');
        $this->interpolate = Config::get($configKey . 'interpolate');
        $this->ignore_all_effects = Config::get($configKey . 'ignore_all_effects');

        if (! is_array($this->background_color) || ! is_string($this->background_color) || strlen($this->background_color) != 7 || $this->background_color{0} != '#') {
            $this->background_color = [];
        }
        if (is_string($this->background_color)) {
            $this->background_color = [
                hexdec($this->background_color{1} . $this->background_color{2}),
                hexdec($this->background_color{3} . $this->background_color{4}),
                hexdec($this->background_color{5} . $this->background_color{6})
            ];
        }
    }

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

        $builder->setDistortion($this->distortion);
        $builder->setBackgroundColor($this->background_color);
        $builder->setBackgroundImages($this->background_images);
        $builder->setInterpolation($this->interpolate);
        $builder->setIgnoreAllEffects($this->ignore_all_effects);
        $builder->{$this->against_ocr ? 'buildAgainstOCR' : 'build'}($this->width, $this->height, $this->font);

        $data = $builder->get($this->quality);
        $phrase = $builder->getPhrase();

        Session::put('captcha_hash', Hash::make(Str::lower($phrase)));

        return Response::make($data)->header('Content-type', 'image/jpeg');
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
        $captcha_hash = (string) Session::get('captcha_hash');
        Session::forget('captcha_hash');
        return $captcha_hash && Hash::check(Str::lower($value), $captcha_hash);
    }

    /**
     * 返回验证码的图片地址。
     * 你可以这样用：
     * <img src="<?php echo Captcha::url(); ?>">
     *
     * @access public
     * @return string
     */
    public static function url()
    {
        $uniqid = uniqid(gethostname(), true);
        $md5 = substr(md5($uniqid), 12, 8); // 8位md5
        $uint = hexdec($md5);
        $uniqid = sprintf('%010u', $uint);
        return URL::to('captcha?' . $uniqid);
    }
}