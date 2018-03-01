<?php

namespace ilateral\SilverStripe\Gallery\Control;

use SilverStripe\ORM\PaginatedList;
use PageController;

class GalleryHubController extends PageController
{

    public function PaginatedChildren()
    {
        $list = $this->AllChildren();
        $limit = $this->ThumbnailsPerPage;

        $pages = PaginatedList::create($list, $this->getRequest());
        $pages->setpageLength($limit);

        return $pages;
    }
}
