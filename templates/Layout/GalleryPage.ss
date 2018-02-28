<% require css(gallery/node_modules/tingle.js/dist/tingle.min.css) %>
<% require css(gallery/css/gallery.min.css) %>
<% require javascript(gallery/node_modules/tingle.js/dist/tingle.min.js) %>
<% require javascript(gallery/javascript/gallery.min.js) %>

<% include SideBar %>

<div class="content-container col-xs-12<% if $Menu(2) %> unit-75 col-md-9<% end_if %>">
    <article class="gallery-page">
        <h1>$Title</h1>

        $Gallery

        <div class="content">$Content</div>
    </article>

    $Form
    $PageComments
</div>
