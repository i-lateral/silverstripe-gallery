<% if $Images %>
    <div class="gallery-thumbnails">
        <div class="row line">
            <% loop $Images %>
                <% with $GalleryThumbnail %>
                    <figure class="col-lg-2 col-md-3 col-xs-6 unit size1of5 <% if $MultipleOf(5) %>lastUnit<% end_if %>">
                        <img
                            class="gallery-thumbnail img-fluid img-responsive"
                            src="{$Link}"
                            alt="{$Title}"
                            data-url="{$Up.GalleryImage.Link}"
                        />
                    </figure>
                <% end_with %>
            <% end_loop %>
        </div>
    </div>

    <% with $Images %>
        <% include Pagination %>
    <% end_with %>
<% end_if %>
