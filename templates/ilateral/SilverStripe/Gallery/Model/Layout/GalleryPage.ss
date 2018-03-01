<% require css("i-lateral/silverstripe-gallery: node_modules/tingle.js/dist/tingle.min.css") %>
<% require css("i-lateral/silverstripe-gallery: client/dist/css/gallery.min.css") %>
<% require javascript("i-lateral/silverstripe-gallery: node_modules/tingle.js/dist/tingle.min.js") %>
<% require javascript("i-lateral/silverstripe-gallery: client/dist/javascript/gallery.min.js) %>

<% if $ShowSideBar && $Menu(2).exists %>
	<% include SideBar %>
<% end_if %>

<div class="content-container col-xs-12 <% if $ShowSideBar && $Menu(2).exists %>col-md-9 size3of4 lastUnit<% end_if %>">
    <article class="gallery-page">
        <h1>$Title</h1>

        $Gallery

        <div class="content">$Content</div>
    </article>

    $Form
    $PageComments
</div>
