<?php

class GalleryPage extends Page {
    static $icon = "gallery/images/gallery.png";

    static $db = array(
        "HideDescription"   => 'Boolean'
    );

    static $many_many = array(
        'Images' => 'Image'
    );

    static $many_many_extraFields = array(
        'Images' => array('SortOrder' => 'Int')
    );

    /**
     * Return sorted images
     *
     * @return ArrayList
     */
    public function SortedImages(){
        return $this->Images();
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->removeByName('HideDescription');

        $upload_folder = Controller::join_links(
            "gallery",
            $this->ID
        );

        $sortable_field = UploadField::create('Images', 'Images to associate with this page')
            ->setFolderName($upload_folder);

        $fields->addFieldToTab("Root.Gallery", $sortable_field);

        return $fields;
    }

    public function getSettingsFields() {
        $fields = parent::getSettingsFields();

        $gallery = FieldGroup::create(
            CheckboxField::create('HideDescription', 'Hide the description of each image?')
        )->setTitle('Gallery');

        $fields->addFieldToTab('Root.Settings', $gallery);

        return $fields;
    }

}
