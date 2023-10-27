<?php

namespace ilateral\SilverStripe\Gallery\Helpers;

use ilateral\SilverStripe\Gallery\Model\Gallery;
use LogicException;
use SilverStripe\Assets\Image;
use SilverStripe\Assets\Storage\DBFile;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

/**
 * Helper library that various objects will
 * use to perform tasks (mainly adjust images).
 */
class GalleryHelper
{
    use Injectable, Configurable;

    /**
     * Image the gallery is manipulating
     *
     * @var Image
     */
    private $image;

    /**
     * Width to adjust image to
     *
     * @var int
     */
    private $width = 900;

    /**
     * Height to adjust image to
     *
     * @var int
     */
    private $height = 500;

    /**
     * Method to use for image adjustment
     * (must be one available on Image)
     *
     * @var string
     */
    private $adjust_method = 'Pad';

    /**
     * Colour to use for the background
     * when padding an image.
     *
     * @var string
     */
    private $background = 'ffffff';

    /**
     * Args to pass to each method call, if you
     * have custom methods on image, then you will need to
     * map them here
     *
     * @var array
     */
    private static $adjust_method_args = [
        'Fill' => ['width','height'],
        'FillMax' => ['width','height'],
        'Pad' => ['width','height','background'],
        'Fit' => ['width','height'],
        'FitMax' => ['width','height'],
        'ResizedImage' => ['width','height'],
        'ScaleWidth' => ['width'],
        'ScaleMaxWidth' => ['width'],
        'CropWidth' => ['width'],
        'ScaleHeight' => ['height'],
        'ScaleMaxHeight' => ['height'],
        'CropHeight' => ['height']
    ];

    public static function getAdjustmentMethods($values = false): array
    {
        /** @var array */
        $methods = Config::inst()->get(
            static::class,
            'adjust_method_args'
        );

        $methods = array_keys($methods);

        if ($values === false) {
            return $methods;
        }

        foreach ($methods as $method) {
            $methods[$method] = $method;
        }

        return $methods;
    }

    public function __construct(Image $image)
    {
        $arguments = Config::inst()->get(
            self::class,
            'adjust_method_args'
        );

        if (!is_array($arguments)) {
            throw new LogicException('Arguments list needs to be a nested array');
        }

        $this->setImage($image);
    }

    protected function findMethodArguments(): array
    {
        /** @var array */
        $arguments = self::getAdjustmentMethods(false);
        $args_list = [];
        $method = $this->getAdjustMethod();

        // Convert the configured list of argument types
        // into values to pass to the resize function
        foreach ($arguments[$method] as $arg) {
            $arg_name = 'get' . ucfirst($arg);
            
            if (!method_exists($this, $arg_name)) {
                throw new LogicException('Method ' . $arg_name . ' not available on GalleryHelper');
            }

            $args_list[] = call_user_func([$this, $arg_name]);
        }

        return $args_list;
    }

    /**
     * Adjust image using current settings and return
     */
    public function adjustImage(): DBFile
    {
        $image = $this->getImage();
        $method = $this->getAdjustMethod();
        $arguments = $this->findMethodArguments();

        if (!method_exists($image, $method)) {
            throw new LogicException('Adjustment method ' . $method . ' not available on Image');
        }

        $adjusted = call_user_func_array(
            [$image, $method],
            $arguments
        );

        return $adjusted;
    }

    public function getImage(): Image
    {
        return $this->image;
    }

    public function setImage(Image $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;
        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight($height): self
    {
        $this->height = $height;
        return $this;
    }

    public function getAdjustMethod(): string
    {
        return $this->adjust_method;
    }

    public function setAdjustMethod(string $method)
    {
        // Ensure passed method has supported args
        $arguments = Config::inst()->get(
            self::class,
            'adjust_method_args'
        );

        if (!array_key_exists($method, $arguments)) {
            throw new LogicException('Method: ' . $method . ' does not have supported arguments');
        }

        $this->adjust_method = $method;
        return $this;
    }

    public function getBackground(): string
    {
        return $this->background;
    }

    public function setBackground(string $hex): self
    {
        $this->background = $hex;
        return $this;
    }
}
