<?php

class GalleryPage_Controller extends GalleryHub_Controller
{
    public function init() {
        parent::init();
    }

    public function PaginatedImages()
    {
        $list = $this->SortedImages();
        $limit = $this->ThumbnailsPerPage;

        $pages = PaginatedList::create($list, $this->getRequest());
        $pages->setpageLength($limit);

        return $pages;
    }

    protected function GalleryImage(Image $image)
    {
        return $this->ScaledImage($image);
    }

    protected function GalleryThumbnail(Image $image)
    {
        return $this->ScaledImage($image, true);
    }

    /**
     * Generate an image gallery from the Gallery template, if no images are
     * available, then return an empty string.
     *
     * @return string
     */
    public function Gallery()
    {
        if ($this->Images()->exists()) {

            // Create a list of images with generated gallery image and thumbnail
            $images = ArrayList::create();
            foreach ($this->PaginatedImages() as $image) {
                $image_data = $image->toMap();
                $image_data["GalleryImage"] = $this->GalleryImage($image);
                $image_data["GalleryThumbnail"] = $this->GalleryThumbnail($image);
                $images->add(ArrayData::create($image_data));
            }
            
            $vars = array(
                'Images' => $images,
                'Width' => $this->ImageWidth,
                'Height' => $this->ImageHeight
            );

            return $this->renderWith('Gallery',$vars);
        } else {
            return "";
        }
    }
}
