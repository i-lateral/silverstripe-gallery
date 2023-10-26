<?php

namespace ilateral\SilverStripe\Gallery\Model;

use SilverStripe\Core\Convert;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Config;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Versioned\Versioned;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\ManyManyThroughList;
use SilverStripe\Forms\GridField\GridField;
use Bummzack\SortableFile\Forms\SortableUploadField;
use ilateral\SilverStripe\Gallery\Model\GalleryImage;

/**
 * @property string Name
 * @property string Code
 * @property int ThumbnailsPerPage
 *
 * @method GalleryPage Page
 * @method ManyManyThroughList Images
 */
class Gallery extends DataObject
{
    private static $table_name = 'Gallery';

    private static $singular_name = 'Gallery';

    private static $plural_name = 'Galleries';

    /**
     * Gallery specific config settings
     * (replaces previous DB based settings)
     */
    private static $thumbnail_width = 150;

    private static $thumbnail_height = 150;

    private static $thumbnails_per_page = 18;

    private static $thumbnail_resize_method = 'Pad';

    private static $thumbnail_padding_background = 'ffffff';

    private static $image_width = 900;

    private static $image_height = 500;

    private static $image_resize_method = 'ScaleMaxHeight';

    private static $image_padding_background = 'ffffff';

    private static $show_image_titles = true;

    private static $db = [
        'Name' => 'Varchar',
        'Code' => 'Varchar'
    ];

    private static $has_one = [
        'Page' => GalleryPage::class
    ];

    private static $many_many = [
        'Images' => [
            'through' => GalleryImage::class,
            'from' => 'Gallery',
            'to' => 'Image'
        ]
    ];

    private static $owns = [
        'Images'
    ];

    private static $defaults = [
        "ThumbnailsPerPage" => 18,
        "ThumbnailResizeType" => 'crop',
        "ImageResizeType" => 'ratio'
    ];

    private static $summary_fields = [
        'ID',
        'Name',
        'Code',
        'Images.count'
    ];

    private static $field_labels = [
        'Page' => 'Link to Gallery Page?',
        'Code' => 'Template code (used in shortcodes/templates)',
        'Images.count' => 'No. of images'
    ];

    private static $extensions = [
        Versioned::class
    ];

    public function forTemplate()
    {
        return $this->renderWith(static::class);
    }

    public function getSortedImages()
    {
        return $this
            ->Images()
            ->sort('SortOrder', 'ASC');
    }

    public function getPaginatedImages()
    {
        $request = Injector::inst()
            ->get(HTTPRequest::class);
        $list = $this->getSortedImages();
        $limit = Config::inst()->get(
            static::class,
            'thumbnails_per_page'
        );

        $pages = PaginatedList::create($list, $request);
        $pages->setpageLength($limit);

        return $pages;
    }

    public function getCMSFields()
    {
        $self =& $this;

        $this->beforeUpdateCMSFields(function ($fields) use ($self) {

            /** @var GridField */
            $images_field = $fields->dataFieldByName('Images');
            $code_field = $fields->dataFieldByName('Code');

            if (!empty($code_field)) {
                $code_field
                    ->setReadonly(true)
                    ->performReadonlyTransformation();
            }

            // Setup settings tab
            $fields->addFieldsToTab(
                'Root.Settings',
                [
                    $code_field,
                    $self
                        ->relObject('PageID')
                        ->scaffoldFormField($self->fieldLabel('Page'))
                ]
            );

            if (!empty($images_field)) {
                $upload_folder = Controller::join_links(
                    "gallery",
                    "object",
                    $self->ID
                );

                $new_images_field = SortableUploadField::create(
                        'Images',
                        $this->fieldLabel('Images')
                )->setFolderName($upload_folder);

                $fields
                    ->removeByName('Images')
                    ->addFieldToTab(
                        'Root.Main',
                        $new_images_field
                    );
            }
        });

        return parent::getCMSFields();
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        // Set the template code that can be
        // called from shortcodes
        $this->Code = Convert::raw2url($this->Name);
    }
}
