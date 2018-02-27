<?php

class GalleryImage extends DataExtension {
    private static $belogs_many_many = array(
        'Gallery'   => 'GalleryPage'
    );
}
