<?php

namespace ilateral\SilverStripe\Gallery\Control;

use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\PaginatedList;
use PageController;

class GalleryPageController extends PageController
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

    /**
     * Generate an image based on the provided type
     * (either )
     *
     * @param Image $image
     * @param string $thumbnail generate a smaller image (based on thumbnail settings)
     * @return void
     */
    protected function ScaledImage(Image $image, $thumbnail = false)
    {
        $img = false;
        $background = $this->PaddedImageBackground;
        
        if ($thumbnail) {
            $resize_type = $this->ThumbnailResizeType;
            $width = $this->ThumbnailWidth;
            $height = $this->ThumbnailHeight;
        } else {
            $resize_type = $this->ImageResizeType;
            $width = $this->ImageWidth;
            $height = $this->ImageHeight;
        }

        switch ($resize_type) {
            case 'crop':
                $img = $image->CroppedImage($width,$height);
                break;
            case 'pad':
                $img = $image->PaddedImage($width,$height,$background);
                break;
            case 'ratio':
                $img = $image->SetRatioSize($width,$height);
                break;
        }

        return $img;
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

            return $this->renderWith(
                [
                    'Gallery',
                    'ilateral\SilverStripe\Gallery\Includes\Gallery'
                ],
                $vars
            );
        } else {
            return "";
        }
    }
}
