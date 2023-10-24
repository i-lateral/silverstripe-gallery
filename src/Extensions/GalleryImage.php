<?php

namespace ilateral\SilverStripe\Gallery\Extensions;

use SilverStripe\ORM\DataExtension;
use ilateral\SilverStripe\Gallery\Model\GalleryPage;

class GalleryImage extends DataExtension
{
    private static $belongs_many_many = [
        'Gallery'   => GalleryPage::class
    ];
}
