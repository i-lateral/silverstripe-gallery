<?php

namespace ilateral\SilverStripe\Gallery\Model;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Versioned\Versioned;

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

    private static $many_many = [
        'Images' => Image::class
    ];

    private static $extensions = [
        Versioned::class
    ];
}