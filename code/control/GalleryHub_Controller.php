<?php
class GalleryHub_Controller extends Page_Controller
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
