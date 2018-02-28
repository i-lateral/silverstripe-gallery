<?php

class GalleryPage_Controller extends Page_Controller
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
        $resize_type = $this->ImageResizeType;
        $width = $this->ImageWidth;
        $height = $this->ImageHeight;
        $background = $this->PaddedImageBackground;
        $img = false;

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

    protected function GalleryThumbnail(Image $image)
    {
        $resize_type = $this->ThumbnailResizeType;
        $width = $this->ThumbnailWidth;
        $height = $this->ThumbnailHeight;
        $background = $this->PaddedImageBackground;

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
                'HideDescription' => $this->HideDescription,
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
