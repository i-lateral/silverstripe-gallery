<?php

/**
 * Generate a page that can display it's children as a grid of thumbnails
 * 
 * @package gallery
 */
class GalleryHub extends Page
{

    /**
     * @var string
     */
    private static $description = 'Display child galleries as a thumbnail grid';

    private static $icon = "gallery/images/gallery-hub.png";

    /**
     * @var array
     */
    private static $allowed_children = array(
        'GalleryPage',
    );

    private static $db = array(
        "ShowSideBar" => "Boolean",
        "ShowImageTitles" => "Boolean",
        "ThumbnailWidth" => "Int",
        "ThumbnailHeight" => "Int",
        "ThumbnailResizeType" => "Enum(array('crop','pad','ratio','width','height'), 'crop')",
        "PaddedImageBackground" => "Varchar",
        "ThumbnailsPerPage" => "Int"
    );

    private static $defaults = array(
        "ThumbnailWidth" => 150,
        "ThumbnailHeight" => 150,
        "ThumbnailsPerPage" => 18,
        "PaddedImageBackground" => "ffffff",
        "ThumbnailsPerPage" => 18
    );

    public function getSettingsFields()
    {
        $fields = parent::getSettingsFields();

        $fields->addFieldsToTab(
            "Root.Settings",
            [
                CheckboxField::create('ShowSideBar'),
                CheckboxField::create('ShowImageTitles'),
                NumericField::create("ThumbnailWidth"),
                NumericField::create("ThumbnailHeight"),
                DropdownField::create("ThumbnailResizeType")
                    ->setSource($this->dbObject("ThumbnailResizeType")->enumValues()),
                NumericField::create('ThumbnailsPerPage'),
                TextField::create("PaddedImageBackground")
            ]
        );

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        // default settings (if not set)
        $defaults = $this->config()->defaults;
        $this->ThumbnailWidth = ($this->ThumbnailWidth) ? $this->ThumbnailWidth : $defaults["ThumbnailWidth"];
        $this->ThumbnailHeight = ($this->ThumbnailHeight) ? $this->ThumbnailHeight : $defaults["ThumbnailHeight"];
        $this->ThumbnailsPerPage = ($this->ThumbnailsPerPage) ? $this->ThumbnailsPerPage : $defaults["ThumbnailsPerPage"];
        $this->PaddedImageBackground = ($this->PaddedImageBackground) ? $this->PaddedImageBackground : $defaults["PaddedImageBackground"];
    }
}
