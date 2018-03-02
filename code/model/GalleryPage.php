<?php

/**
 * A single page that can display many images as thumbnails.
 * 
 * @package gallery
 */
class GalleryPage extends Page
{

    /**
     * @var string
     */
    private static $description = 'Display a "gallery" of images';

    private static $icon = "gallery/images/gallery.png";

    private static $db = array(
        "ImageWidth" => "Int",
        "ImageHeight" => "Int",
        "ImageResizeType" => "Enum(array('crop','pad','ratio','width','height'), 'ratio')",
        "ThumbnailWidth" => "Int",
        "ThumbnailHeight" => "Int",
        "ThumbnailResizeType" => "Enum(array('crop','pad','ratio','width','height'), 'crop')",
        "ThumbnailsPerPage" => "Int",
        "PaddedImageBackground" => "Varchar",
        "ShowSideBar" => "Boolean"
    );

    private static $defaults = array(
        "ImageWidth" => 950,
        "ImageHeight" => 500,
        "ThumbnailWidth" => 150,
        "ThumbnailHeight" => 150,
        "ThumbnailsPerPage" => 18,
        "PaddedImageBackground" => "ffffff",
        "ShowSideBar" => 1
    );

    private static $many_many = array(
        'Images' => 'Image'
    );

    private static $many_many_extraFields = array(
        'Images' => array('SortOrder' => 'Int')
    );

    /**
     * Return sorted images
     *
     * @return ArrayList
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
                SortableUploadField::create(
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
            array(
                NumericField::create("ImageWidth"),
                NumericField::create("ImageHeight"),
                DropdownField::create("ImageResizeType")
                    ->setSource($this->dbObject("ImageResizeType")->enumValues()),
                NumericField::create("ThumbnailWidth"),
                NumericField::create("ThumbnailHeight"),
                DropdownField::create("ThumbnailResizeType")
                    ->setSource($this->dbObject("ThumbnailResizeType")->enumValues()),
                NumericField::create('ThumbnailsPerPage'),
                TextField::create("PaddedImageBackground"),
                CheckboxField::create('ShowSideBar')
            )
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
        $this->ThumbnailWidth = ($this->ThumbnailWidth) ? $this->ThumbnailWidth : $defaults["ThumbnailWidth"];
        $this->ThumbnailHeight = ($this->ThumbnailHeight) ? $this->ThumbnailHeight : $defaults["ThumbnailHeight"];
        $this->ThumbnailsPerPage = ($this->ThumbnailsPerPage) ? $this->ThumbnailsPerPage : $defaults["ThumbnailsPerPage"];
        $this->PaddedImageBackground = ($this->PaddedImageBackground) ? $this->PaddedImageBackground : $defaults["PaddedImageBackground"];
    }

}
