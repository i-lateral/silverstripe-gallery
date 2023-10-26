<?php

namespace ilateral\SilverStripe\Gallery\Extensions;

use ilateral\SilverStripe\Gallery\Model\Gallery;
use SilverStripe\ORM\DataExtension;

class GalleryImageExtension extends DataExtension
{
    private static $belongs_many_many = [
        'Galleries' => Gallery::class . 'Images'
    ];
}
