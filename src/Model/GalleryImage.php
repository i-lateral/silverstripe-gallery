<?php

namespace ilateral\SilverStripe\Gallery\Model;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Core\Config\Config;
use SilverStripe\Versioned\Versioned;
use ilateral\SilverStripe\Gallery\Helpers\GalleryHelper;

/**
 * @property int SortOrder
 * 
 * @method Image Image
 * @method Gallery Gallery
 */
class GalleryImage extends DataObject
{
    private static $table_name = 'GalleryImage';

    private static $db = [
        'SortOrder' => 'Int'
    ];

    private static $has_one = [
        'Image' => Image::class,
        'Gallery' => Gallery::class
    ];

    private static $default_sort = 'SortOrder';

    private static $extensions = [
        Versioned::class
    ];

    public function getGalleryThumbnail()
    {
        $image = $this->Image();
        $width = Config::inst()->get(
            Gallery::class,
            'thumbnail_width'
        );
        $height = Config::inst()->get(
            Gallery::class,
            'thumbnail_height'
        );
        $adjust_method = Config::inst()->get(
            Gallery::class,
            'thumbnail_resize_method'
        );
        $background = Config::inst()->get(
            Gallery::class,
            'thumbnail_padding_background'
        );

        $helper = GalleryHelper::create($image)
            ->setWidth($width)
            ->setHeight($height)
            ->setAdjustMethod($adjust_method)
            ->setBackground($background);

        return $helper->adjustImage();
    }

    public function getGalleryImage()
    {
        $image = $this->Image();
        $width = Config::inst()->get(
            Gallery::class,
            'image_width'
        );
        $height = Config::inst()->get(
            Gallery::class,
            'image_height'
        );
        $adjust_method = Config::inst()->get(
            Gallery::class,
            'image_resize_method'
        );
        $background = Config::inst()->get(
            Gallery::class,
            'image_padding_background'
        );

        $helper = GalleryHelper::create($image)
            ->setWidth($width)
            ->setHeight($height)
            ->setAdjustMethod($adjust_method)
            ->setBackground($background);

        return $helper->adjustImage();
    }
}
