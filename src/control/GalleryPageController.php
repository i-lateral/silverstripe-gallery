<?php

namespace ilateral\SilverStripe\Gallery\Control;

use PageController;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\FieldType\DBField;

class GalleryPageController extends GalleryHubController
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
            $pages = $this->PaginatedImages();
            foreach ($this->PaginatedImages() as $image) {
                $image_data = $image->toMap();
                $image_data["GalleryImage"] = $this->GalleryImage($image);
                $image_data["GalleryThumbnail"] = $this->GalleryThumbnail($image);
                $images->add(ArrayData::create($image_data));
            }
            
            $vars = [
                'PaginatedImages' => $pages,
                'Images' => $images,
                'Width' => $this->getFullWidth(),
                'Height' => $this->getFullHeight()
            ];

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

    /**
     * Using $UserDefinedForm in the Content area of the page shows
     * where the form should be rendered into. If it does not exist
     * then default back to $Form.
     *
     * @return array
     */
    public function index(HTTPRequest $request = null)
    {
        $gallery = $this->Gallery();
        if ($this->Content && $gallery) {
            $hasLocation = stristr($this->Content, '$Gallery');
            if ($hasLocation) {
                /** @see Requirements_Backend::escapeReplacement */
                $galleryEscapedForRegex = addcslashes($gallery->forTemplate(), '\\$');
                $content = preg_replace(
                    '/(<p[^>]*>)?\\$Gallery(<\\/p>)?/i',
                    $galleryEscapedForRegex,
                    $this->Content
                );
                return [
                    'Content' => DBField::create_field('HTMLText', $content),
                    'Gallery' => ''
                ];
            }
        }

        return [
            'Content' => DBField::create_field('HTMLText', $this->Content),
            'Gallery' => $this->Gallery()
        ];
    }
}
