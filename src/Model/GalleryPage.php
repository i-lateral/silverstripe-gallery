<?php

namespace ilateral\SilverStripe\Gallery\Model;

use SilverStripe\Dev\Deprecation;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\DropdownField;
use SilverShop\HasOneField\HasOneButtonField;
use Bummzack\SortableFile\Forms\SortableUploadField;
use ilateral\SilverStripe\Gallery\Control\GalleryPageController;
use SilverShop\HasOneField\GridFieldHasOneUnlinkButton;

/**
 * A single page that can display many images as thumbnails.
 * 
 * @property int ImageWidth
 * @property int ImageHeight
 * @property string ImageResizeType
 * 
 * @method Gallery Gallery
 */
class GalleryPage extends GalleryHub
{
    private static $description = 'Display a "gallery" of images';

    private static $icon_class = 'font-icon-p-gallery-alt';

    private static $table_name = "GalleryPage";

    private static $db = [
        "ImageWidth" => "Int",
        "ImageHeight" => "Int",
        "ImageResizeType" => "Enum(array('crop','pad','ratio','width','height'), 'ratio')"
    ];

    private static $has_one = [
        'Gallery' => Gallery::class
    ];

    private static $owns = [
        'Gallery'
    ];

    private static $cascade_deletes = [
        'Gallery'
    ];

    private static $defaults = [
        "ImageWidth" => 950,
        "ImageHeight" => 500,
        "ImageResizeType" => 'ratio',
        "ShowSideBar" => 1
    ];

    public function getControllerName() {
        return GalleryPageController::class;
    }

    /**
     * Is there a gallery embeded in the content area
     * rather than allowing it to render seperartly
     * 
     * @return bool
     */
    public function isGalleryEmbeded(): bool
    {
        return stristr($this->Content, '$Gallery');
    }

    public function Images()
    {
        Deprecation::notice('3.0');
        return $this->getImages();
    }

    public function getImages()
    {
        return $this->Gallery()->Images();
    }

    public function SortedImages()
    {
        return $this->Gallery()->getSortedImages();
    }

    public function getCMSFields()
    {
        $self =& $this;

        $this->beforeUpdateCMSFields(function ($fields) use ($self) {
            $fields->removeByName([
                "ImageWidth",
                "ImageHeight",
                "ImageResizeType"
            ]);

            if (!$self->canEdit()) {
                return;
            }

            $gallery = HasOneButtonField::create($this, 'Gallery');
            $gallery
                ->getConfig()
                ->removeComponentsByType(GridFieldHasOneUnlinkButton::class);

            $fields->insertBefore(
                'Content',
                $gallery
            );

            $fields->removeByName('HideDescription');
        });

        return parent::getCMSFields();
    }

    public function getSettingsFields()
    {
        $fields = parent::getSettingsFields();
        $fwidth = $this->config()->get('force_image_width');
        $fheight = $this->config()->get('force_image_height');
        $fresize = $this->config()->get('force_image_resize_type');

        $new_fields = [];

        if ($fwidth == null) {
            $new_fields[] = NumericField::create("ImageWidth");
        }
        if ($fheight == null) {
            $new_fields[] = NumericField::create("ImageHeight");
        }
        if ($fresize == null) {
            $new_fields[] = DropdownField::create("ImageResizeType")
                ->setSource($this->dbObject("ImageResizeType")->enumValues());
        }

        $fields->addFieldsToTab(
            'Root.Settings',
            $new_fields
        );

        return $fields;
    }

    public function getFullWidth()
    {
        $forced = $this->config()->get('force_image_width');
        if ($forced != null) {
            return $forced;
        }

        return $this->ImageWidth;
    }

    public function getFullHeight()
    {
        $forced = $this->config()->get('force_image_height');
        if ($forced != null) {
            return $forced;
        }

        return $this->ImageHeight;
    }

    public function getFullResize()
    {
        $forced = $this->config()->get('force_image_resize_type');
        if ($forced != null) {
            return $forced;
        }

        return $this->ImageResizeType;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        // default settings (if not set)
        $defaults = $this->config()->defaults;
        $this->ImageWidth = ($this->getFullWidth()) ? $this->getFullWidth() : $defaults["ImageWidth"];
        $this->ImageHeight = ($this->getFullHeight()) ? $this->getFullHeight() : $defaults["ImageHeight"];
    
        if (!$this->Gallery()->exists()) {
            $gallery = Gallery::create([
                'Name' => $this->Title
            ]);
            $gallery->write();
            $this->GalleryID = $gallery->ID;
        } else {
            $this->Gallery()->Name = $this->Title;
            $this->Gallery()->write();
        }
    }
}
