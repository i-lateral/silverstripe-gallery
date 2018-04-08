<?php

namespace ilateral\SilverStripe\Gallery\Model;

use Page;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\TextField;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use ilateral\SilverStripe\Gallery\Control\GalleryPageController;

/**
 * A single page that can display many images as thumbnails.
 * 
 * @package gallery
 */
class GalleryPage extends GalleryHub
{
    /**
     * @var string
     */
    private static $description = 'Display a "gallery" of images';

    private static $icon = "resources/i-lateral/silverstripe-gallery/client/dist/images/gallery.png";

    private static $table_name = "GalleryPage";

    private static $db = [
        "ImageWidth" => "Int",
        "ImageHeight" => "Int",
        "ImageResizeType" => "Enum(array('crop','pad','ratio','width','height'), 'ratio')"
    ];

    private static $defaults = [
        "ImageWidth" => 950,
        "ImageHeight" => 500,
        "ImageResizeType" => 'ratio',
        "ShowSideBar" => 1
    ];

    private static $many_many = [
        'Images' => Image::class
    ];

    private static $many_many_extraFields = [
        'Images' => ['SortOrder' => 'Int']
    ];

    private static $owns = [
        'Images'
    ];

    public function getControllerName() {
        return GalleryPageController::class;
    }

    /**
     * Return sorted images
     *
     * @return SSList
     */
    public function SortedImages()
    {
        return $this->Images()->Sort('SortOrder');
    }

    public function getCMSFields()
    {
        $self =& $this;
        
        $this->beforeUpdateCMSFields(function ($fields) use ($self) {
            if (!$self->canEdit()) {
                return;
            }
            
            $fields->removeByName('HideDescription');
            
            $upload_folder = Controller::join_links(
                "gallery",
                $self->ID
            );
            
            $fields->addFieldToTab(
                "Root.Gallery",
                UploadField::create(
                    'Images',
                    $this->fieldLabel('Images')
                )->setFolderName($upload_folder)
            );
        });
            
        return parent::getCMSFields();
    }

    public function getSettingsFields() {
        $fields = parent::getSettingsFields();

        $fields->addFieldsToTab(
            'Root.Settings',
            [
                NumericField::create("ImageWidth"),
                NumericField::create("ImageHeight"),
                DropdownField::create("ImageResizeType")
                    ->setSource($this->dbObject("ImageResizeType")->enumValues())
            ]
        );

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        // default settings (if not set)
        $defaults = $this->config()->defaults;
        $this->ImageWidth = ($this->ImageWidth) ? $this->ImageWidth : $defaults["ImageWidth"];
        $this->ImageHeight = ($this->ImageHeight) ? $this->ImageHeight : $defaults["ImageHeight"];
    }

}
