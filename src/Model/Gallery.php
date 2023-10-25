<?php

namespace ilateral\SilverStripe\Gallery\Model;

use SilverStripe\ORM\DataObject;
use SilverStripe\Versioned\Versioned;

class Gallery extends DataObject
{
    private static $table_name = 'Gallery';

    private static $singular_name = 'Gallery';

    private static $plural_name = 'Galleries';

    private static $db = [
        'Name' => 'Varchar'
    ];

    private static $has_one = [
        'Page' => GalleryPage::class
    ];

    private static $many_many = [
        'Images' => [
            'through' => GalleryImage::class,
            'from' => 'Gallery',
            'to' => 'Image'
        ]
    ];

    private static $extensions = [
        Versioned::class
    ];
}