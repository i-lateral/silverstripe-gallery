<% require css("i-lateral/silverstripe-gallery: node_modules/tingle.js/dist/tingle.min.css") %>
<% require css("i-lateral/silverstripe-gallery: client/dist/css/gallery.min.css") %>
<% require javascript("i-lateral/silverstripe-gallery: node_modules/tingle.js/dist/tingle.min.js") %>
<% require javascript("i-lateral/silverstripe-gallery: client/dist/javascript/gallery.min.js") %>

<% if $Images.exists %>
    <div class="gallery-thumbnails">
        <div class="row line">
            <% loop $PaginatedImages %>
                <div class="col-lg-2 col-md-3 col-6 unit size1of5 <% if $MultipleOf(5) %>lastUnit<% end_if %>">
                    <figure>
                        <span data-url="{$Join.GalleryImage.Link}">
                            <img
                                class="gallery-thumbnail img-fluid img-responsive"
                                src="{$Join.GalleryThumbnail.Link}"
                                alt="{$Title}"
                            />
                        </span>
                        <% if $ShowImageTitles %>
                            <figcaption>$Title</figcaption>
                        <% end_if %>
                    </figure>
                </div>
            <% end_loop %>
        </div>
    </div>

    <% with $PaginatedImages %>
        <% include ilateral\SilverStripe\Gallery\Includes\Pagination %>
    <% end_with %>
<% end_if %>
