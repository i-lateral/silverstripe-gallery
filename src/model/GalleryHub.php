<?php

namespace ilateral\SilverStripe\Gallery\Model;

use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\CheckboxField;
use Page;

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

    private static $icon = "resources/i-lateral/silverstripe-gallery/client/dist/images/gallery-hub.png";

    private static $table_name = "GalleryHub";

    /**
     * @var array
     */
    private static $allowed_children = [
        GalleryPage::class,
    ];

    private static $db = [
        "ShowSideBar" => "Boolean",
        "ThumbnailsPerPage" => "Int"
    ];

    private static $defaults = [
        "ThumbnailsPerPage" => 18
    ];

    public function getSettingsFields()
    {
        $fields = parent::getSettingsFields();

        $fields->addFieldsToTab(
            "Root.Settings",
            [
                NumericField::create(
                    'ThumbnailsPerPage',
                    $this->fieldLabel("ThumbnailsPerPage")
                ),
                CheckboxField::create(
                    'ShowSideBar',
                    $this->fieldLabel("ShowSideBar")
                )
            ]
        );

        return $fields;
    }
}
